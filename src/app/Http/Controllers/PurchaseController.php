<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function create($item_id)
    {
        $product = Product::findOrFail($item_id);
        $user = Auth::user();
        $profile = $user->profile ?? new \App\Models\Profile();
        $shipping = session('shipping_address_' . $item_id);

        if (request()->has('payment')) {
            session(['payment' => request('payment')]);
        }

        if (!request()->has('payment')) {
            session()->forget('payment');
        }

        $payment = session('payment');

        return view('purchase.purchase', compact('product', 'user', 'profile', 'shipping', 'payment'));
    }

    public function store(AddressRequest $request, $item_id)
    {
        $data = $request->validated();

        $data['building'] = $request->input('building') ?? '';

        session(['shipping_address_' . $item_id => $data]);

        $user = Auth::user();

        return redirect()->route('purchase.create', $item_id);
    }

    public function address($item_id)
    {
        $product = Product::findOrFail($item_id);

        return view('purchase.address', compact('product'));
    }

    public function checkout(PurchaseRequest $request, Product $product)
    {
        if ($product->user_id === Auth::id()) {
        abort(403, '自分の商品は購入できません');
        }

        if ($product->is_sold) {
            abort(403, '売り切れです');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentMethod = $request->payment ?? session(['payment_' . $product->id => request('payment')]);

        if ($paymentMethod === 'credit') {
            $methods = ['card'];
        } elseif ($paymentMethod === 'convenience') {
            $methods = ['konbini'];
        }

        $session = Session::create([
            'payment_method_types' => $methods,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' =>
                route('purchase.success')
                . '?product_id=' . $product->id
                . '&payment=' . $request->payment,
            'cancel_url' => route('purchase.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $productId = $request->product_id;
        $paymentMethod = $request->payment;

        $address = session('shipping_address_' . $productId) ?? session('shipping_address');

        $profile = $user->profile;

        if (!$address) {
            $address = [
                'post_code' => optional($profile)->post_code,
                'address' => optional($profile)->address,
                'building' => optional($profile)->building,
            ];
        }

        $user->purchases()->create([
            'product_id' => $productId,
            'payment_method' => $paymentMethod,                'post_code' => $address['post_code'],
            'address' => $address['address'],
            'building' =>$address['building'] ?? null,
        ]);

        $product = Product::find($productId);

        if ($product) {
            $product->is_sold = true;
            $product->save();
        }

        session()->forget('shipping_address_' . $productId);

        return redirect()->route('products.index');
    }

    public function cancel()
    {
        return view('purchase.cancel');
    }
}
