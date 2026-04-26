<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use Database\Seeders\ConditionSeeder;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ConditionSeeder::class);
    }

    public function test_全商品表示が表示される()
    {
        $user1 = User::create([
            'name' => '出品者1',
            'email' => 'seller1@test.com',
            'password' => bcrypt('password'),
        ]);

        $user2 = User::create([
            'name' => '出品者2',
            'email' => 'seller2@test.com',
            'password' => bcrypt('password'),
        ]);

        Product::create([
            'name' => '商品A',
            'description' => 'テスト商品',
            'price' => 1000,
            'user_id' => $user1->id,
            'image' => 'dummy.jpg',
            'condition_id' => 1,
        ]);

        Product::create([
            'name' => '商品B',
            'description' => 'テスト商品',
            'price' => 1000,
            'user_id' => $user2->id,
            'image' => 'dummy.jpg',
            'condition_id' => 1,
        ]);

        $response = $this->get('/?tab=recommend');

        $response->assertStatus(200);

        $response->assertSee('商品A');
        $response->assertSee('商品B');
    }

    public function test_購入済み商品はSoldと表示される()
    {
        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@test.com',
            'password' => bcrypt('password'),
        ]);

        $buyer = User::create([
            'name' => '購入者',
            'email' => 'buyer@test.com',
            'password' => bcrypt('password'),
        ]);

        $product = Product::create([
            'name' => '売れた商品',
            'description' => 'テスト商品',
            'price' => 1000,
            'user_id' => $seller->id,
            'image' => 'dummy.jpg',
            'condition_id' => 1,
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'payment_method' => 'credit',
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);

        $response = $this->get('/?tab=recommend');

        $response->assertStatus(200);

        $response->assertSee('売れた商品');
        $response->assertSee('Sold');
    }

    public function test_自分の商品は表示されない()
    {
        $viewer = User::create([
            'name' => 'ログインユーザー',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);

        $otherUser = User::create([
            'name' => '他人',
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($viewer);

        Product::create([
            'name' => '自分の商品',
            'description' => 'テスト商品',
            'price' => 1000,
            'user_id' => $viewer->id,
            'image' => 'dummy.jpg',
            'condition_id' => 1,
        ]);

        Product::create([
            'name' => '他人の商品',
            'description' => 'テスト商品',
            'price' => 1000,
            'user_id' => $otherUser->id,
            'image' => 'dummy.jpg',
            'condition_id' => 1,
        ]);

        $response = $this->get('/?tab=recommend');

        $response->assertStatus(200);

        $response->assertSee('他人の商品');
        $response->assertDontSee('自分の商品');
    }
}
