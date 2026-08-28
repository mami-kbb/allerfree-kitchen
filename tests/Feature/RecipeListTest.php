<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\AllergySeeder;
use Database\Seeders\IngredientSeeder;
use App\Models\Recipe;
use App\Models\User;
use Tests\TestCase;

class RecipeListTest extends TestCase
{
    use RefreshDatabase;

    //マスターデータのみ入れる
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([AllergySeeder::class, IngredientSeeder::class]);
    }

    public function test_recipe_list_shows_recipes(): void
    {
        Recipe::factory()->count(10)->create(['status' => 1]);
        Recipe::factory()->count(3)->create(['status' => 0]);
        Recipe::factory()->count(2)->create(['status' => 2]);

        $response = $this->get(route('recipes.list'));

        $recipes = $response->viewData('recipes');
        $this->assertCount(10, $recipes);
    }

    public function test_recipe_my_list_show_favorite_recipes(): void
    {
        $user = User::factory()->create();

        $likedRecipe = Recipe::factory()->create(['status' => 1]);
        $notLikedRecipe = Recipe::factory()->create(['status' => 1]);

        $likedRecipe->likes()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertSee($likedRecipe->name);
        $response->assertDontSee($notLikedRecipe->name);
    }

    public function test_guest_see_login_message_on_favorite_tab(): void
    {
        $response = $this->get('/?tab=mylist');

        $response->assertSee('ログイン');
        $response->assertSee('するとお気に入りを管理できます');
    }
}
