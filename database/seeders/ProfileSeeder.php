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

        Profile::create([
            'user_id' => 2,
            'comment' => '卵と乳成分のアレルギーの子を持つ母です。',
        ]);

        Profile::create([
            'user_id' => 3,
            'comment' => '趣味は料理です。いろいろなアレルギーに対応したレシピを投稿していきます。',
        ]);
    }
}
