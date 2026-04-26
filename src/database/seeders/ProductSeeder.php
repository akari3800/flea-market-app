<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Product::create([
            'user_id' => 1,
            'name' => '腕時計',
            'price' => 15000,
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'image' => 'products/ArmaniClock.jpg',
            'condition_id' => 1,
        ]);
        $product->categories()->attach([1, 5]);

        $product = Product::create([
            'user_id' => 1,
            'name' => 'HDD',
            'price' => 5000,
            'brand_name' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'image' => 'products/HDDHardDisk.jpg',
            'condition_id' => 2,
        ]);
        $product->categories()->attach([2]);

        $product = Product::create([
            'user_id' => 1,
            'name' => '玉ねぎ3束',
            'price' => 300,
            'brand_name' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'image' => 'products/3onions.jpg',
            'condition_id' => 3,
        ]);
        $product->categories()->attach([10]);

        $product = Product::create([
            'user_id' => 1,
            'name' => '革靴',
            'price' => 4000,
            'brand_name' => '',
            'description' => 'クラシックなデザインの革靴',
            'image' => 'products/LeatherShoes.jpg',
            'condition_id' => 4,
        ]);
        $product->categories()->attach([1, 5]);

        $product = Product::create([
            'user_id' => 1,
            'name' => 'ノートPC',
            'price' => 45000,
            'brand_name' => '',
            'description' => '高性能なノートパソコン',
            'image' => 'products/LivingRoomLaptop.jpg',
            'condition_id' => 1,
        ]);
        $product->categories()->attach([2]);

        $product = Product::create([
            'user_id' => 1,
            'name' => 'マイク',
            'price' => 8000,
            'brand_name' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'image' => 'products/MusicMic.jpg',
            'condition_id' => 2,
        ]);
        $product->categories()->attach([2]);

        $product = Product::create([
            'user_id' => 1,
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand_name' => '',
            'description' => 'おしゃれなショルダーバッグ',
            'image' => 'products/shoulderBag.jpg',
            'condition_id' => 3,
        ]);
        $product->categories()->attach([1, 4]);

        $product = Product::create([
            'user_id' => 1,
            'name' => 'タンブラー',
            'price' => 500,
            'brand_name' => 'なし',
            'description' => '使いやすいタンブラー',
            'image' => 'products/Tumbler.jpg',
            'condition_id' => 4,
        ]);
        $product->categories()->attach([10]);

        $product = Product::create([
            'user_id' => 1,
            'name' => 'コーヒーミル',
            'price' => 4000,
            'brand_name' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'image' => 'products/CoffeeGrinder.jpg',
            'condition_id' => 1,
        ]);
        $product->categories()->attach([10]);

        $product = Product::create([
            'user_id' => 1,
            'name' => 'メイクセット',
            'price' => 2500,
            'brand_name' => '',
            'description' => '便利なメイクアップセット',
            'image' => 'products/MakeupSet.jpg',
            'condition_id' => 2,
        ]);
        $product->categories()->attach([6]);
    }
}
