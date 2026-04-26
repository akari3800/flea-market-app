<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねすると登録されカウント数が増える()
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

        $response = $this->postJson(route('like.toggle', $product->id));

        $response->assertStatus(200)
            ->assertJson([
                'liked' => true,
                'count' => 1,
                ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_いいね解除ができる()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test2@test.com',
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

        $this->postJson(route('like.toggle', $product->id));

        $response = $this->postJson(route('like.toggle', $product->id));

        $response->assertStatus(200)
            ->assertJson([
                'liked' => false,
                'count' => 0,
                ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_いいねの再度押下で解除されいいね数は減る()
    {
        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@test.com',
            'password' => bcrypt('password'),
        ]);

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create([
            'name' => '新品'
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

        $this->actingAs($user);

        $this->postJson(route('like.toggle', $product->id));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->postJson(route('like.toggle', $product->id));

        $response->assertJson([
            'liked' => false,
            'count' => 0,
        ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

}
