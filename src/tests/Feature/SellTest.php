<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

class SellTest extends TestCase
{
    use RefreshDatabase;

    public function test_出品商品が正しく保存される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        $condition = Condition::create([
            'name' => '良好'
        ]);

        $category = Category::create([
            'name' => 'ファッション',
        ]);

        $file = UploadedFile::fake()->create('test.jpg', 100);

        $response = $this->post('/sell', [
            'image' => $file,
            'name' => 'テスト商品',
            'description' => '商品説明',
            'brand' => 'テストブランド',
            'price' => 1000,
            'condition' => $condition->id,
            'categories' => [$category->id],
        ]);

        $response->assertRedirect('/mypage');

        $this->assertDatabaseHas('products', [
            'name' => 'テスト商品',
            'description' => '商品説明',
            'brand_name' => 'テストブランド',
            'price' => 1000,
            'condition_id' => $condition->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('category_product', [
            'category_id' => $category->id,
            'product_id' => Product::first()->id,
        ]);
    }
}
