<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class SellController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('sell', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->brand_name = $request->brand;
        $product->price = $request->price;
        $product->condition_id = $request->condition;

        if($request->file('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->user_id = Auth::id();

        $product->save();

        $product->categories()->attach($request->categories);

        return redirect('/mypage');
    }

}
