<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\AllergySeeder;
use Database\Seeders\AllergyCategorySeeder;
use Database\Seeders\IngredientSeeder;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Allergy;

class SearchRecipesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([AllergySeeder::class, AllergyCategorySeeder::class, IngredientSeeder::class]);
    }

    public function test_user_can_search_recipe_by_name(): void
    {
        $targetRecipe = Recipe::factory()->create([
            'name' => 'じゃがいもとブロッコリーのガーリック炒め',
            'status' => 1,
            ]);
        $recipe = Recipe::factory()->create(['status' => 1]);

        $response = $this->get('/?keyword=ブロッコリー');
        $response->assertSee($targetRecipe->name);
        $response->assertDontSee($recipe->name);
    }

    public function test_user_can_search_recipe_by_ingredient(): void
    {
        $targetRecipe = Recipe::factory()->create(['status' =>1]);
        $recipe = Recipe::factory()->create(['status' => 1]);

        $egg = Ingredient::where('name', '卵')->first();
        $targetRecipe->ingredients()->attach($egg->id, ['quantity' => '1個',]);
        $recipe->ingredients()->attach(3, ['quantity' => '100cc',]);

        $response = $this->get('/?keyword=卵');

        $response->assertSee($targetRecipe->name);
        $response->assertDontSee($recipe->name);
    }

    public function test_user_can_search_recipe_by_name_or_ingredient(): void
    {
        $nameRecipe = Recipe::factory()->create([
            'name' => 'じゃがいもとブロッコリーのガーリック炒め',
            'status' => 1,
            ]);
        $ingredientRecipe = Recipe::factory()->create(['status' => 1]);
        $recipe = Recipe::factory()->create(['status' => 1]);
        $broccoli = Ingredient::where('name', 'ブロッコリー')->first();
        $potato = Ingredient::where('name', 'じゃがいも')->first();

        $nameRecipe->ingredients()->attach($potato->id, ['quantity' => '1個']);
        $ingredientRecipe->ingredients()->attach($broccoli->id, ['quantity' => '1房']);
        $recipe->ingredients()->attach($potato->id, ['quantity' => '1個']);

        $response = $this->get('/?keyword=ブロッコリー');

        $response->assertSee($nameRecipe->name);
        $response->assertSee($ingredientRecipe->name);
        $response->assertDontSee($recipe->name);
    }

    public function test_keep_keyword_on_favorite_tab(): void
    {
        $response = $this->get('/?keyword=卵');

        $response->assertSee('お気に入り');
        $response->assertSee('tab=mylist');

        $response = $this->get('/?keyword=卵&tab=mylist');

        $response->assertSee('卵');
    }

    public function test_user_can_search_by_selected_exclusion_allergy(): void
    {
        $excludedRecipe = Recipe::factory()->create(['status' => 1]);
        $recipe = Recipe::factory()->create(['status' => 1]);

        $egg = Allergy::where('name', '卵')->first();
        $milk = Allergy::where('name', '乳成分')->first();

        $excludedRecipe->allergies()->attach($egg->id);
        $recipe->allergies()->attach($milk->id);

        $query = http_build_query([
            'allergy_recipe' => [$egg->id],
        ]);

        $response = $this->get('/?' . $query);

        $response->assertDontSee($excludedRecipe->name);
        $response->assertSee($recipe->name);
    }

    public function test_user_can_exclude_recipes_with_multiple_allergies(): void
    {
        $excludedRecipe1 = Recipe::factory()->create(['status' => 1]);
        $excludedRecipe2 = Recipe::factory()->create(['status' => 1]);
        $recipe = Recipe::factory()->create(['status' => 1]);

        $egg = Allergy::where('name', '卵')->first();
        $milk = Allergy::where('name', '乳成分')->first();
        $gluten = Allergy::where('name', '小麦')->first();

        $excludedRecipe1->allergies()->attach($egg->id);
        $excludedRecipe2->allergies()->attach($milk->id);
        $recipe->allergies()->attach($gluten->id);

        $query = http_build_query([
            'allergy_recipe' => [
                $egg->id,
                $milk->id,
            ],
        ]);

        $response = $this->get('/?'. $query);

        $response->assertDontSee($excludedRecipe1->name);
        $response->assertDontSee($excludedRecipe2->name);
        $response->assertSee($recipe->name);
    }

    public function test_user_can_search_recipe_by_name_and_allergies(): void
    {
        $eggRecipe = Recipe::factory()->create([
            'name' => 'じゃがいもしりしり',
            'status' => 1
            ]);
        $noEggRecipe = Recipe::factory()->create([
            'name' => 'ふかしじゃがいも',
            'status' => 1]);

        $egg = Allergy::where('name', '卵')->first();
        $milk = Allergy::where('name', '乳成分')->first();

        $eggRecipe->allergies()->attach($egg->id);
        $noEggRecipe->allergies()->attach($milk->id);

        $query = http_build_query([
            'keyword' => 'じゃがいも',
            'allergy_recipe' => [$egg->id],
        ]);

        $response = $this->get('/?' . $query);

        $response->assertDontSee($eggRecipe->name);
        $response->assertSee($noEggRecipe->name);
    }
}
