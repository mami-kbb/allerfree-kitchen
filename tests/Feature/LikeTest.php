<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Recipe;
use App\Models\User;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_likes_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['status' => 1]);

        $response = $this->actingAs($user)->post(route('like', ['recipe' => $recipe->id]));

        $response->assertStatus(302);
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_liked_icon_changes_color(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['status' => 1]);

        $response = $this->actingAs($user)->get(route('recipe.show', ['recipe_id' => $recipe->id]));

        $response->assertSee('/images/heart_logo.png');

        $this->actingAs($user)->post(route('like', ['recipe' => $recipe->id]));

        $response = $this->actingAs($user)->get(route('recipe.show', ['recipe_id' => $recipe->id]));
        $response->assertSee('/images/heart_logo_pink.png');
    }

    public function test_user_can_cancel_like_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['status' => 1]);

        $recipe->likes()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('unlike', ['recipe' => $recipe->id]));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }
}
