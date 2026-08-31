<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    private function validLoginParams(array $overrides = []): array
    {
        return array_merge([
            'email' => "test@example.com",
            'password' => "password",
        ], $overrides);
    }

    private function createTestUser(): User
    {
        return User::factory()->create([
            'email' => "test@example.com",
            'password' => "password",
        ]);
    }

    public function test_login_validate_email(): void
    {
        $response = $this->post(route('login'), $this->validLoginParams(['email' =>""]));

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_login_validate_password(): void
    {
        $response = $this->post(route('login'), $this->validLoginParams(['password' => ""]));

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_login_validate_user(): void
    {
        $this->createTestUser();

        $response = $this->post(route('login'), $this->validLoginParams(['email' => "test@test.com"]));

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません。']);
    }

    public function test_login_success(): void
    {
        $user = $this->createTestUser();

        $response = $this->post(route('login'), $this->validLoginParams());

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }
}
