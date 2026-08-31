<?php

namespace Tests\Feature;

use App\Models\Allergy;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\IngredientSeeder;
use Database\Seeders\AllergySeeder;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Override;
use Tests\TestCase;

class RecipeCrudTest extends TestCase
{
    use RefreshDatabase;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([AllergySeeder::class, IngredientSeeder::class]);
    }

    public function test_user_can_store_recipe(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $egg = Allergy::where('name', '卵')->first();
        $potato = Ingredient::where('name', 'じゃがいも')->first();

        $image = UploadedFile::fake()->image('soborodon.jpg');

        $response = $this->actingAs($user)->post(route('recipe.store'), [
            'name' => '野菜たっぷり 和風そぼろ丼',
            'image' => $image,
            'description' => '甘辛いそぼろと彩り豊かな野菜をご飯にのせた、栄養バランスの良いどんぶりです。',
            'servings' => '2人分',
            'tips' => 'ほうれん草は最後に加えると色鮮やかに仕上がります。',
            'allergy_recipe' => [
                $egg->id,
            ],
            'ingredients' => [
                $potato->name,
            ],
            'quantities' => [
                '1個',
            ],
            'steps' => [
                '材料を一口サイズに切ります'
            ],
        ]);

        $response->assertRedirectToRoute('profile', [
            'user_id' => $user->id,
        ]);

        $recipe = Recipe::where('name', '野菜たっぷり 和風そぼろ丼')->first();

        $this->assertNotNull($recipe);

        $this->assertDatabaseHas('allergy_recipe', [
            'recipe_id' => $recipe->id,
            'allergy_id' => $egg->id,
        ]);

        $this->assertDatabaseHas('ingredient_recipe', [
            'recipe_id' => $recipe->id,
            'ingredient_id' => $potato->id,
            'quantity' => '1個',
        ]);

        $this->assertDatabaseHas('steps', [
            'recipe_id' => $recipe->id,
            'step_number' => 1,
            'content' => '材料を一口サイズに切ります',
        ]);
    }

    public function test_not_login_user_cannot_access_recipe_create_page(): void
    {
        $response = $this->get(route('recipe.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_recipe_edit_display_saved_information(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'status' => 1,
            ]);

        $egg = Allergy::where('name', '卵')->first();
        $milk = Allergy::where('name', '乳成分');
        $potato = Ingredient::where('name', 'じゃがいも')->first();

        $recipe->allergies()->attach($egg->id);
        $recipe->ingredients()->attach($potato->id, ['quantity' => '1個']);
        $recipe->steps()->create([
            'step_number' => 1,
            'content' => '材料を一口サイズに切ります',
        ]);

        $response = $this->actingAs($user)->get(route('recipe.edit', ['recipe_id' => $recipe->id]));

        $response->assertSee(asset('storage/' . $recipe->image));
        $response->assertSee($recipe->name);
        $response->assertSee($recipe->description);
        $response->assertSee($recipe->servings);
        $response->assertSee($potato->name);
        $response->assertSee('1個');
        $response->assertSee($recipe->tips);
        $response->assertSee('材料を一口サイズに切ります');
        $response->assertSee(
            'id="allergy_' . $egg->id . '" value="' . $egg->id . '" name="allergy_recipe[]" checked', false
        );
        $response->assertDontSee(
            'id="allergy_' . $milk->id . '" value="' . $milk->id . '" name="allergy_recipe[]" checked', false
        );
    }

    public function test_user_cannot_access_other_recipe_edit_page(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $recipe = Recipe::factory()->create([
            'user_id' => $user1->id,
            'status' => 1,
        ]);

        $response = $this->actingAs($user2)->get(route('recipe.edit', ['recipe_id' => $recipe->id]));

        $response->assertStatus(403);
    }

    public function test_delete_recipe_and_redirect_to_profile(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'status' => 1,
            ]);

        $response = $this->actingAs($user)->post(
            route('recipe.delete', ['recipe_id' => $recipe->id])
            );

        $response->assertRedirectToRoute('profile', [
            'user_id' => $user->id,
            ]);
        $response->assertSessionHas(
            'delete','レシピを削除しました'
            );
        $this->assertDatabaseMissing('recipes', [
            'recipe_id' => $recipe->id,
        ]);
    }
}
