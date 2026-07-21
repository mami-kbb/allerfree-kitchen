<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Recipe;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $steps = require database_path('data/steps.php');
        $recipeIds = Recipe::pluck('id', 'name');
        $now = now();

        DB::table('steps')->insert(
            collect($steps)
            ->map(fn ($step) => [
                'recipe_id' => $recipeIds[$step['recipe']],
                'step_number' => $step['step_number'],
                'content' => $step['content'],
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->toArray()
        );
    }
}
