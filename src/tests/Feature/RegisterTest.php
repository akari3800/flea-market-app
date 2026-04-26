<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前未入力でエラー()
    {
        $response = $this->post('/register',[
            'name' => '',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_メール未入力でエラー()
    {
        $response = $this->post('/register',[
            'name' => 'テスト',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_パスワード未入力でエラー()
    {
        $response = $this->post('/register',[
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => '',
            'password_confirmation' => ''
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_パスワード7文字以下でエラー()
    {
        $response = $this->post('/register',[
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => '1234567',
            'password_confirmation' => '1234567'
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_パスワード不一致でエラー()
    {
        $response = $this->post('/register',[
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => '1234567'
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_正常登録()
    {
        $response = $this->post('/register',[
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com'
        ]);
    }
}

