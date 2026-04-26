<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class LikeController extends Controller
{
    public function toggle(Product $product)
    {
        if(auth()->check() && !auth()->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email verification required'], 403);
        }

        $user = auth()->user();

        $like = $product->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $product->likes()->create([
                'user_id' => $user->id
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $product->likes()->count()
        ]);
    }
}
