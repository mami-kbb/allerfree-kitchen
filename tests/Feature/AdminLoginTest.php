<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    private function validLoginParams(array $overrides = []): array
    {
        return array_merge([
            'email' => "admin@example.com",
            'password' => "password",
        ], $overrides);
    }

    private function createTestAdmin(): User
    {
        return User::factory()->create([
            'email' => "admin@example.com",
            'password' => "password",
            'role' => "admin",
        ]);
    }

    public function test_admin_login_validate_email(): void
    {
        $response = $this->post('/admin/login', $this->validLoginParams(['email' =>""]));

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_admin_login_validate_password(): void
    {
        $response = $this->post('/admin/login', $this->validLoginParams(['password' => ""]));

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_admin_login_validate_user(): void
    {
        $this->createTestAdmin();

        $response = $this->post('/admin/login', $this->validLoginParams(['email' => "admin@test.com"]));

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません。']);
    }

    public function test_admin_login_success(): void
    {
        $admin = $this->createTestAdmin();

        $response = $this->post('/admin/login', $this->validLoginParams());

        $response->assertRedirect('/admin/recipes');

        //'admin'ガードでログインしているかチェック
        $this->assertAuthenticatedAs($admin, 'admin');
    }
}
