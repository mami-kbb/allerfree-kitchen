<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //データを別けて管理することで、seederの登録の役割が明確になる。
        $ingredients = require database_path('data/ingredients.php');
        $now = now(); //同じ時間になるので最初に取得

        DB::table('ingredients')->insert(
            collect($ingredients)
            ->map(fn($ingredient) => [
                ...$ingredient,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->toArray()
        );
    }
}
