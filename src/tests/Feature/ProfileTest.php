<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_プロフィール編集画面に初期値が表示される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $user->profile()->create([
            'image' => 'profile.jpg',
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('value="テストユーザー"', false);

        $response->assertSee('value="123-4567"', false);

        $response->assertSee('value="東京都"', false);

        $response->assertSee('value="テストビル"', false);

        $response->assertSee('profile.jpg');
    }
}
