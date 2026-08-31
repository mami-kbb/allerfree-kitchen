<?php

namespace Tests\Feature;

use App\Models\Allergy;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\AllergySeeder;
use Database\Seeders\IngredientSeeder;
use App\Models\Recipe;
use App\Models\User;
use Tests\TestCase;

class RecipeShowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([AllergySeeder::class, IngredientSeeder::class]);
    }

    public function test_recipe_detail_display_recipe_information(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $recipe = Recipe::factory()->create(['status'  => 1]);

        $egg = Allergy::where('name', '卵')->first();
        $potato = Ingredient::where('name', 'じゃがいも')->first();

        $recipe->allergies()->attach($egg->id);
        $recipe->ingredients()->attach($potato->id, ['quantity' => '1個']);
        $recipe->steps()->create([
            'step_number' => 1,
            'content' => '材料を一口サイズに切ります',
        ]);
        $recipe->likes()->create(['user_id' => $user1->id]);
        $recipe->comments()->create([
            'user_id' => $user2->id,
            'comment' => 'おいしそうですね。'
        ]);

        $response = $this->get(route('recipe.show', ['recipe_id' => $recipe->id]));

        $response->assertOk();
        $response->assertSee($recipe->image);
        $response->assertSee($recipe->name);
        $response->assertSee($recipe->description);
        $response->assertSee($egg->name);
        $response->assertSee($recipe->servings);
        $response->assertSee($potato->name);
        $response->assertSee('1個');
        $response->assertSee('材料を一口サイズに切ります');
        $response->assertSee($recipe->tips);
        $response->assertSee((string) $recipe->likes()->count());
        $response->assertSee((string) $recipe->comments()->count());
        $response->assertSee($user2->name);
        $response->assertSee('おいしそうですね。');
    }

    public function test_recipe_detail_displays_multiple_allergies(): void
    {
        $recipe = Recipe::factory()->create(['status'  => 1]);
        $egg = Allergy::where('name', '卵')->first();
        $milk = Allergy::where('name', '乳成分')->first();
        $gluten = Allergy::where('name', '小麦')->first();

        $recipe->allergies()->attach($egg->id);$recipe->allergies()->attach($milk->id);

        $response = $this->get(route('recipe.show', ['recipe_id' => $recipe->id]));

        $response->assertSee($egg->name);
        $response->assertSee($milk->name);
        $response->assertDontSee($gluten->name);
    }

    public function test_owner_can_see_edit_button_on_recipe_detail(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'status' => 1
            ]);

        $response = $this->actingAs($user)->get(route('recipe.show', ['recipe_id' => $recipe->id]));

        $response->assertSee('レシピを編集');
    }

    public function test_other_user_cannot_see_edit_button_on_recipe_detail(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $recipe = Recipe::factory()->create([
            'user_id' => $user1->id,
            'status' => 1
            ]);

        $response = $this->actingAs($user2)->get(route('recipe.show', ['recipe_id' => $recipe->id]));

        $response->assertDontSee('レシピを編集');
    }
}
