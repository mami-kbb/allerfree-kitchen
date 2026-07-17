<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Allergy;

class AllergyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 2,
                'allergy' => '卵',
            ],
            [
                'user_id' => 2,
                'allergy' => '乳成分',
            ],
            [
                'user_id' => 3,
                'allergy' => 'いくら',
            ],
            [
                'user_id' => 3,
                'allergy' => 'サケ',
            ],
        ];

        $allergyIds = Allergy::pluck('id', 'name');
        $now = now();

        DB::table('allergy_user')->insert(
            collect($data)
            ->map(fn ($row) => [
                'user_id' => $row['user_id'],
                'allergy_id' => $allergyIds[$row['allergy']],
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->toArray()
        );
    }
}
