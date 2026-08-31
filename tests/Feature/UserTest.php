<?php

namespace Tests\Feature;

use App\Models\Allergy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\AllergySeeder;
use App\Models\User;
use App\Models\Recipe;
use Override;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([AllergySeeder::class]);
    }

    public function test_user_can_see_profile_information(): void
    {
        $user = User::factory()->create();
        $user->profile()->create([
            'profile_image' => '/images/test.png',
            'comment' => 'hello'
        ]);

        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'status' => 1,
        ]);

        $response = $this->get(route('profile', ['user_id' => $user->id]));

        $response->assertSee($user->name);
        $response->assertSee('/images/test.png');
        $response->assertSee('hello');
        $response->assertSee($recipe->name);
    }

    public function test_owner_can_see_edit_button_on_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile', ['user_id' => $user->id]));

        $response->assertSee('プロフィールを編集');
    }

    public function test_other_user_cannot_see_edit_button_on_profile(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user2)->get(route('profile', ['user_id' => $user1->id]));

        $response->assertDontSee('プロフィールを編集');
    }

    public function test_profile_edit_display_saved_information(): void
    {
        $user = User::factory()->create();
        $user->profile()->create([
            'profile_image' => '/images/test.png',
            'comment' => 'hello',
        ]);

        $egg = Allergy::where('name', '卵')->first();
        $milk = Allergy::where('name', '乳成分')->first();

        $user->allergies()->attach($egg->id);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertOk();

        $response->assertSee($user->name);
        $response->assertSee('/images/test.png');
        $response->assertSee('hello');

        //HTMLとしてそのまま検索するためにfalseを指定する
        $response->assertSee(
            'id="allergy_' . $egg->id . '" value="' . $egg->id . '" name="allergy_user[]" checked', false
        );
        $response->assertDontSee(
            'id="allergy_' . $milk->id . '" value="' . $milk->id . '" name="allergy_user[]" checked', false
        );
    }
}
