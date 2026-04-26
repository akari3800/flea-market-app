<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\CommentRequest;


class CommentController extends Controller
{
    public function store(CommentRequest $request, Product $product)
    {
        $product->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->input('comment')
            ]);

        return redirect()->back();
    }
}
