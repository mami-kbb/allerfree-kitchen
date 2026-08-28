<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    //正常のベースデータを作成して、各テストで上書きすることで重複を減らす
    private function validParams(array $overrides = []): array
    {
        return array_merge([
            'name' => "Test User",
            'email' => "test@example.com",
            'password' => "password",
            'password_confirmation' => "password",
        ], $overrides);
    }

    public function test_register_validate_name(): void
    {
        $response = $this->post('/register', $this->validParams(['name' => ""]));

        $response->assertSessionHasErrors(['name' =>'ユーザー名を入力してください']);
    }

    public function test_register_validate_email(): void
    {
        $response = $this->post('/register', $this->validParams(['email' => ""]));

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_register_validate_password(): void
    {
        $response = $this->post('/register', $this->validParams(['password' => ""]));

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_register_validate_password_under8(): void
    {
        $response = $this->post('/register', $this->validParams(['password' => "pass"]));

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_register_validate_confirm_password(): void
    {
        $response = $this->post('/register', $this->validParams(['password_confirmation' => "password123"]));

        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
    }

    public function test_user_can_register(): void
    {
        $params = $this->validParams();

        $response = $this->post('/register', $params);

        $response->assertRedirect('/mypage/edit');

        $this->assertDatabaseHas('users', [
            'name' => $params['name'],
            'email' => $params['email'],
        ]);

        $this->assertAuthenticated();
    }
}