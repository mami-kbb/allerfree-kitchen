<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProfileSeeder::class,
            AllergySeeder::class,
            IngredientSeeder::class,
            AllergyCategorySeeder::class,
            RecipeSeeder::class,
            IngredientRecipeSeeder::class,
            AllergyUserSeeder::class,
            AllergyRecipeSeeder::class,
            StepSeeder::class,
            AllergyCategoryIngredientSeeder::class,
        ]);
    }
}
