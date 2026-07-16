<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllergyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('allergy_user')->insert([
            [
                'user_id' => 2,
                'allergy_id' => 2,
            ],
            [
                'user_id' => 2,
                'allergy_id' => 3,
            ],
            [
                'user_id' => 3,
                'allergy_id' => 13,
            ],
            [
                'user_id' => 13,
                'allergy_id' => 19,
            ],
        ]);
    }
}
