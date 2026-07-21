<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Recipe;
use App\Models\Ingredient;

class IngredientRecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = require database_path('data/ingredient_recipe.php');
        $recipeIds = Recipe::pluck('id', 'name');
        $ingredientIds = Ingredient::pluck('id', 'name');
        $now = now();

        DB::table('ingredient_recipe')->insert(
            collect($data)
            ->map(fn ($row) => [
                'recipe_id' => $recipeIds[$row['recipe']],
                'ingredient_id' => $ingredientIds[$row['ingredient']],
                'quantity' => $row['quantity'],
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->toArray()
        );
    }
}
