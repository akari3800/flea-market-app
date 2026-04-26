<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;

class LikeSeeder extends Seeder
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
            Like::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }
    }
}
