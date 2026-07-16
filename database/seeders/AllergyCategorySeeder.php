<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllergyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergyCategories = require database_path('data/allergy_categories.php');
        $now = now();

        DB::table('allergy_categories')->insert(
            collect($allergyCategories)
            ->map(fn($allergyCategory) => [
                ...$allergyCategory,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->toArray()
        );
    }
}
