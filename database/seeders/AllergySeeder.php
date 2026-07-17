<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergies = require database_path('data/allergies.php');
        $now = now();
        DB::table('allergies')->insert(
            collect($allergies)
            ->map(fn($allergy) => [
                ...$allergy,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->toArray()
        );
        //map()で配列の1件1件を加工してインサートする
        //...$allergyでデータの配列をそのまま展開してくれる
    }
}
