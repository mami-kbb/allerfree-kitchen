<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('profiles')->exists()) {
            return;
        }

        //今後userが増える可能性を考慮してループ処理
        foreach ([2, 3] as $userId) {
            Profile::create([
                'user_id' => $userId,
                'comment' => fake()->text(100),
            ]);
        }
    }
}
