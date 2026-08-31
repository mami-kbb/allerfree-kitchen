<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Recipe;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_user_can_send_comment(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['status' => 1]);

        $response = $this->actingAs($user)->post(route('comment', ['recipe' => $recipe->id]), [
            'comment' => 'おいしそうですね',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('comments',[
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'comment' => 'おいしそうですね',
        ]);
    }

    public function test_not_login_user_can_not_send_comment(): void
    {
        $recipe = Recipe::factory()->create(['status' => 1]);

        $response = $this->post(route('comment', ['recipe' => $recipe->id]), [
            'comment' => 'おいしそうですね',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', [
            'recipe_id' => $recipe->id,
            'comment' => 'おいしそうですね',
        ]);
    }

    public function test_comment_validate_comment(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['status' => 1]);

        $response = $this->actingAs($user)->post(route('comment', ['recipe' => $recipe->id]), [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment' =>'コメントを入力してください']);
    }

    public function test_comment_validate_max300(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['status' => 1]);

        $response = $this->actingAs($user)->post(route('comment', ['recipe' => $recipe->id]), [
            'comment' => str_repeat('あ', 301),
        ]);

        $response->assertSessionHasErrors(['comment' =>'コメントは300文字以内で入力してください']);
    }
}
