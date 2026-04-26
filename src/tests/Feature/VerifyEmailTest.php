<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\URL;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_会員登録後に認証メールが送信される()
    {
        Notification::fake();

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => null,
        ]);

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_認証メール誘導画面が表示される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test2@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/email/verify');

        $response->assertStatus(200);
        $response->assertSee('認証はこちらから');
        $response->assertSee('認証メールを再送する');
    }

    public function test_メール認証完了でプロフィールへ遷移する()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test3@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->get($url);

        $response->assertRedirect('/mypage/profile');

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
