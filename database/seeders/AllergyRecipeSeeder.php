<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Allergy;
use App\Models\Recipe;

class AllergyRecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = require database_path('data/allergy_recipe.php');
        $recipeIds = Recipe::pluck('id','name');
        $allergyIds = Allergy::pluck('id', 'name');
        $now =now();

        DB::table('allergy_recipe')->insert(
            collect($data)
            ->map(fn ($row) => [
                'recipe_id' => $recipeIds[$row['recipe']],
                'allergy_id' => $allergyIds[$row['allergy']],
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->toArray()
        );
    }
}
