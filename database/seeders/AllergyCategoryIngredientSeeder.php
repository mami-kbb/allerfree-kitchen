<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AllergyCategory;
use App\Models\Ingredient;

class AllergyCategoryIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = require database_path('data/allergy_category_ingredients.php');

        //dataで受け取った名前の値からidに変換する。

        $ingredientIds = Ingredient::pluck('id', 'name');
        $allergyCategoryIds = AllergyCategory::pluck('id', 'name');
        $now = now();

        DB::table('allergy_category_ingredient')->insert(
            collect($data)
            ->map(fn ($row) => [
                'ingredient_id' => $ingredientIds[$row['ingredient']],
                'allergy_category_id' => $allergyCategoryIds[$row['allergy_category']],
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->toArray()
        );
    }
}
