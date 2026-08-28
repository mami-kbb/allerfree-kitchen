<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    private function validParams(array $overrides = []): array
    {
        return array_merge([
            'name' => "Test User",
            'email' => "test@example.com",
            'password' => "password",
            'password_confirmation' => "password",
        ], $overrides);
    }

    public function test_verification_email_sent_after_register(): void
    {
        //以降、送信した記録だけを記録するモード
        Notification::fake();

        $response = $this->post('/register', $this->validParams());

        $user = User::where('email', 'test@example.com')->first();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_verification_notice_page_has_mailhog_link(): void{
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/email/verify');

        //第二引数にfalseを指定することで、HTMLエスケープをしない
        $response->assertSee('href="http://localhost:8025"', false);
        $response->assertSee('認証はこちらから');
    }

    public function test_email_verification_completed_and_redirects_to_profile(): void
    {
        $user = User::factory()->unverified()->create();

        //メールで送られる認証リンクのURLを手動で再現
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);
        $response->assertRedirect('/mypage/edit?verified=1');
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
