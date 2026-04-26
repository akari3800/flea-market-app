<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;


class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct($user)
    {
        $condition = Condition::create([
            'name' => '良好'
        ]);

        return Product::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'is_sold' => false,
        ]);
    }

    public function test_カード支払いが小計画面に反映される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $user->profile()->create([
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $product = $this->createProduct($user);

        $this->actingAs($user);

        $response = $this->get(route('purchase.create',[
            'item_id' => $product->id,
            'payment' => 'credit'
        ]));

        $response->assertStatus(200);
        $response->assertSee('カード支払い');
    }

    public function test_コンビニ払いが小計画面に反映される()
    {
        $user = User::create([
            'name' => 'テストユーザー2',
            'email' => 'test2@test.com',
            'password' => bcrypt('password'),
        ]);

        $user->profile()->create([
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $product = $this->createProduct($user);

        $this->actingAs($user);

        $response = $this->get(route('purchase.create',[
            'item_id' => $product->id,
            'payment' => 'convenience'
        ]));

        $response->assertStatus(200);
        $response->assertSee('コンビニ払い');
    }

    public function test_支払い方法未選択が小計画面に反映される()
    {
        $user = User::create([
            'name' => 'テストユーザー3',
            'email' => 'test3@test.com',
            'password' => bcrypt('password'),
        ]);

        $user->profile()->create([
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $product = $this->createProduct($user);

        $this->actingAs($user);

        $response = $this->get(route('purchase.create',[
            'item_id' => $product->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('未選択');
    }
}
