<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $isVerifiedUser = Auth::check() && Auth::user()->hasVerifiedEmail();

        $tab = $request->tab ?? ($isVerifiedUser ? 'mylist' : 'recommend');
        $keyword = $request->keyword;

        if ($tab === 'mylist' && $isVerifiedUser) {

            $query = Auth::user()
                ->likes()
                ->with('product')
                ->whereHas('product', function ($q) use ($keyword) {

                    $q->where('user_id', '!=', Auth::id());

                    if ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%');
                    }
                });

            $products = $query->get()->pluck('product');

        } elseif ($tab === 'mylist') {
            $products = collect();

        } else {

            $query = Product::query();

            if ($isVerifiedUser) {
            $query->where('user_id', '!=', Auth::id());
            }

            if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
            }
            $products = $query->get();
        }

        return view('products',compact('products'));
    }

    public function show($item_id)
    {
        $product = Product::with([
            'likes',
            'comments.user.profile',
            'categories',
            'condition'
            ])->findOrFail($item_id);

        return view('show', compact('product'));
    }

}
