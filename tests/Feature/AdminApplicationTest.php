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
use Tests\TestCase;

class AdminApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([AllergySeeder::class, IngredientSeeder::class]);
    }

    public function test_user_can_apply_for_a_recipe(): void
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

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'status' => 0,
        ]);

        $response = $this->get('/');
        $response->assertDontSee($recipe->name);
    }

    public function test_user_can_see_pending_recipe_on_pending_tab(): void
    {
        $user = User::factory()->create();

        $pendingRecipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'status' => 0,
            ]);

        $response = $this->actingAs($user)->get(route('profile',['user_id' => $user->id, 'tab' => 'pending']));
        $response->assertSee($pendingRecipe->name);
    }

    public function test_admin_can_approve_recipe():void
    {
        $egg = Allergy::where('name', '卵')->first();
        $potato = Ingredient::where('name', 'じゃがいも')->first();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $recipe = Recipe::factory()->create([
            'status' => 0,
        ]);
        $recipe->allergies()->attach($egg->id);
        $recipe->ingredients()->attach($potato->id, ['quantity' => '1個']);
        $recipe->steps()->create([
            'step_number' => 1,
            'content' => '材料を一口サイズに切ります',
        ]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.recipe.approve', ['recipe_id' => $recipe->id]));

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'status' => 1,
        ]);

        $response = $this->get('/');
        $response->assertSee($recipe->name);
    }

    public function test_admin_can_reject_recipe():void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create();

        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'status' => 0,
        ]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.recipe.reject', [
            'recipe_id' => $recipe->id,
            'rejection_reason' => 'アレルギーを正しく設定してください',
            ])
        );

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'status' => 2,
            'rejection_reason' => 'アレルギーを正しく設定してください',
        ]);

        $response = $this->actingAs($user)->get(route('profile', [
            'user_id' => $user->id,
            'tab' => 'reject',
            ])
        );

        $response->assertSee($recipe->name);
    }

    public function test_cannot_reject_recipe_without_rejection_reason(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $recipe = Recipe::factory()->create([
            'status' => 0,
        ]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.recipe.reject', [
            'recipe_id' => $recipe->id,
            'rejection_reason' => '',
            ]));

        $response->assertSessionHasErrors([
            'rejection_reason' => '差戻し理由を入力してください',
            ]);

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'status' => 0,
        ]);
    }
}
