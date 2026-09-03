<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('recipes')->exists()) {
            return;
        }
        //sample-imagesの画像をstorage/appにimagesディレクトリ（公開用ディレクトリ）を作成してコピーする
        $recipes = require database_path('data/recipes.php');

        $now = now();

        DB::table('recipes')->insert(
            collect($recipes)
            ->map(fn($recipe) => [
                ...$recipe,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->toArray()
        );
    }
}
