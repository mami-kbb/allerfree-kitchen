<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_validate_name(): void
    {
        $response = $this->post('/register', [
            'name' => "",
            'email' => "test@example.com",
            'password' => "password",
            'password_confirmation' => "password",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals('ユーザー名を入力してください', $errors->first('name'));
    }
}
