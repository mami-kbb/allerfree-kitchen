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
        //sample-imagesの画像をstorage/appにimagesディレクトリ（公開用ディレクトリ）を作成してコピーする
        $recipes = require database_path('data/recipes.php');
        $disk = Storage::disk('public');
        $disk->makeDirectory('images');

        foreach ($recipes as $recipe) {
            $image = basename($recipe['image']);
            $source = resource_path('sample-images/' . $image);
            $destination = $recipe['image'];

            if (File::exists($source) && !$disk->exists($destination)) {
                $disk->put($destination, File::get($source));
            }
        }

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
