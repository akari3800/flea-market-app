<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Profile;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Support\Facades\Session;

class AddressTest extends TestCase
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

    public function test_住所変更画面での変更が購入画面に反映される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test2@test.com',
            'password' => bcrypt('password'),
        ]);

        $user->profile()->create([
            'post_code' => '111-1111',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $product = $this->createProduct($user);

        $this->actingAs($user);

        $this->post("/purchase/address/{$product->id}", [
            'post_code' => '222-2222',
            'address' => '新住所',
            'building' => '新建物',
        ])->assertRedirect("/purchase/{$product->id}");

        $response = $this->get("/purchase/{$product->id}");

        $response->assertSessionHas('shipping_address_' . $product->id);

        $response->assertSee('222-2222');
        $response->assertSee('新住所');
        $response->assertSee('新建物');
    }

    public function test_購入商品に変更した住所が反映される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $user->profile()->create([
            'post_code' => '111-1111',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $product = $this->createProduct($user);

        $this->actingAs($user);

        Session::put('shipping_address_' . $product->id, [
            'post_code' => '333-3333',
            'address' => '購入住所',
            'building' => '購入建物',
        ]);

        $this->get("/purchase/success?product_id={$product->id}&payment=credit")->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment_method' => 'credit',
            'post_code' => '333-3333',
            'address' => '購入住所',
            'building' => '購入建物',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_sold' => true,
        ]);
    }
}
