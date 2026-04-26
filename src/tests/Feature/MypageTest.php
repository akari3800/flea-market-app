<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use App\Models\Purchase;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    public function test_マイページで必要な情報が取得できる()
    {

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test2@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        $user->profile()->create([
            'post_code' => '111-1111',
            'address' => '東京都',
            'building' => 'テストビル',
            'image' => 'profile.jpg',
        ]);

        $condition = Condition::create([
            'name' => '良好'
        ]);

        $sellProduct = Product::create([
            'user_id' => $user->id,
            'name' => '出品商品',
            'description' => '説明',
            'price' => 1000,
            'image' => 'sell.jpg',
            'condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        $buyer = User::create([
            'name' => '購入者',
            'email' => 'buyer@test.com',
            'password' => bcrypt('password'),
        ]);

        $buyProduct = Product::create([
            'user_id' => $user->id,
            'name' => '購入商品',
            'description' => '説明',
            'price' => 2000,
            'image' => 'buy.jpg',
            'condition_id' => $condition->id,
            'is_sold' => true,
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $buyProduct->id,
            'payment_method' => 'credit',
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'ビル',
        ]);


        $response = $this->get('/mypage?tab=sell');

        $response->assertStatus(200);

        $response->assertSee('テストユーザー');
        $response->assertSee('profile.jpg');

        $response->assertSee('出品商品');

        $response = $this->get('/mypage?tab=buy');

        $response->assertStatus(200);

        $response->assertSee('購入商品');
    }
}
