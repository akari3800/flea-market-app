<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use App\Models\Comment;


class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログイン済みユーザーはコメント送信できる()
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
        ]);

        $this->actingAs($user);

        $response = $this->post("/comment/{$product->id}", [
            'comment' => 'テストコメント'
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'テストコメント',
        ]);
    }

    public function test_ログイン前はコメントできない()
    {
        $condition = Condition::create(['name' => '良好']);

        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@test.com',
            'password' => bcrypt('password'),
        ]);

        $product = Product::create([
            'user_id' => $seller->id,
            'name' => 'テスト商品',
            'brand_name' => 'ブランド',
            'description' => '説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'condition_id' => $condition->id,
        ]);

        $response = $this->post("/comment/{$product->id}", [
            'comment' => 'コメント'
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'comment' => 'コメント',
        ]);
    }

    public function test_コメント未入力でバリデーションエラー()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test2@test.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['name' => '良好']);

        $product = Product::create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand_name' => 'ブランド',
            'description' => '説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'condition_id' => $condition->id,
        ]);

        $this->actingAs($user);

        $response = $this->post("/comment/{$product->id}", [
            'comment' => ''
        ]);

        $response->assertSessionHasErrors('comment');
    }

    public function test_255文字以上はバリデーションエラー()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test3@test.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['name' => '良好']);

        $product = Product::create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand_name' => 'ブランド',
            'description' => '説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'condition_id' => $condition->id,
        ]);

        $this->actingAs($user);

        $longComment = str_repeat('あ', 300);

        $response = $this->post("/comment/{$product->id}", [
            'comment' => $longComment
        ]);

        $response->assertSessionHasErrors('comment');
    }
}
