<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_購入が完了する()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create([
            'name' => '良好'
        ]);

        $product = Product::create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand_name' => 'ブランド',
            'description' => '説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        $this->actingAs($user);

        session([
            'shipping_address' => [
                'post_code' => '123-4567',
                'address' => 'テスト住所',
                'building' => 'テストビル'
            ]
        ]);

        $response = $this->get("/purchase/success?product_id={$product->id}&payment=credit");

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment_method' => 'credit',
        ]);
    }

    public function test_購入した商品はsold表示される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test2@test.com',
            'password' => bcrypt('password'),
        ]);

        $seller = User::create([
            'name' => '出品ユーザー',
            'email' => 'seller@test.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create([
            'name' => '良好'
        ]);

        $product = Product::create([
            'user_id' => $seller->id,
            'name' => 'テスト商品',
            'brand_name' => 'ブランド',
            'description' => '説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        $this->actingAs($user);

        session([
            'shipping_address' => [
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル'
            ]
        ]);

        $response = $this->get("/purchase/success?product_id={$product->id}&payment=credit");

        $response->assertRedirect(route('products.index'));

        $response = $this->get('/?tab=recommend');

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('sold-label');
    }

    public function test_購入商品がマイページに表示される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test3@test.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create([
            'name' => '良好'
        ]);

        $product = Product::create([
            'user_id' => $user->id,
            'name' => '購入商品',
            'brand_name' => 'ブランド',
            'description' => '説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'condition_id' => $condition->id,
        ]);

        $user->profile()->create([
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル',
            'image' => 'test.jpg',
        ]);

        $user->purchases()->create([
            'product_id' => $product->id,
            'payment_method' => 'credit',
            'post_code' => '123-4567',
            'address' => 'テスト住所2',
            'building' => 'テストビル2',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('購入した商品');
    }
}
