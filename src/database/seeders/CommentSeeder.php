<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', 'admin')->first();

        $products = Product::all();

        foreach ($products as $product) {
            Comment::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'comment' => 'こちらにコメントが入ります。',
            ]);
        }
    }
}
