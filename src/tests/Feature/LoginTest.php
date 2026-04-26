<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_メール未入力でエラー()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password'
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_パスワード未入力でエラー()
    {
        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => ''
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_ログイン情報相違でエラー()
    {
        User::create([
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => Hash::make('password')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_正常ログイン()
    {
        $user = User::create([
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => Hash::make('password')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'password'
        ]);

        $response->assertRedirect(route('products.index', ['tab' => 'mylist']));

        $this->assertAuthenticatedAs($user);
    }
}
