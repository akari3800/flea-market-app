<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Like;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品詳細に必要な情報が表示される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create([
            'name' => '新品'
        ]);

        $category1 = Category::create(['name' => 'ファッション']);
        $category2 = Category::create(['name' => '家電']);

        $product = Product::create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテストです',
            'price' => 1000,
            'image' => 'test.jpg',
            'condition_id' => $condition->id,
        ]);

        $product->categories()->attach([$category1->id, $category2->id]);

        Like::create([
            'user_id' => $user->id,
            'product_id' =>$product->id,
        ]);

        Comment::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'テストコメント',
        ]);

        $response = $this->get(route('item.show', $product->id));

        $response->assertStatus(200);

        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('1,000');
        $response->assertSee('これはテストです');

        $response->assertSee('ファッション');
        $response->assertSee('家電');

        $response->assertSee('新品');

        $response->assertSee('1');

        $response->assertSee('テストコメント');
        $response->assertSee('テストユーザー');
    }

    public function test_複数カテゴリが表示される()
    {
        $user = User::create([
            'name' => 'ユーザー',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create([
            'name' => '良好'
        ]);

        $category1 = Category::create(['name' => '本']);
        $category2 = Category::create(['name' => 'ゲーム']);

        $product = Product::create([
            'user_id' => $user->id,
            'name' => 'カテゴリテスト商品',
            'brand_name' => '',
            'description' => 'テスト説明',
            'price' => 2000,
            'image' => 'test.jpg',
            'condition_id' => $condition->id,
        ]);

        $product->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get("/item/{$product->id}");

        $response->assertStatus(200);

        $response->assertSee('本');
        $response->assertSee('ゲーム');
    }
}

