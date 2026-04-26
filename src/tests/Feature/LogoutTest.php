<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログアウトできる()
    {
        $user = User::create([
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');

        $this->assertGuest();
    }
}
