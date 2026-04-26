<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $profile = $user->profile ?? new \App\Models\Profile();

        $tab = $request->tab ?? 'sell';
        $keyword = $request->keyword;

        if ($tab === 'buy') {

            $query = $user->purchases()->with('product');

            if (!empty($keyword)) {
                $query->whereHas('product', function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
                });
            }

            $products = $query->get()->pluck('product')->filter();

        } else {

            $query = Product::where('user_id', $user->id);

            if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
            }

            $products = $query->get();
        }

        return view('mypage.mypage', compact('user', 'profile', 'products'));
    }
}
