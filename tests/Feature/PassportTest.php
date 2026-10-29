<?php

namespace Tests\Feature;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PassportTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_digital_passport_renders_correctly(): void
    {
        $this->seed();

        $player = Player::first();
        $this->assertNotNull($player);

        $admin = \App\Models\User::where('role', 'admin')->first() ?? \App\Models\User::first();
        $response = $this->actingAs($admin)->get('/passport/' . $player->uuid);
        $response->assertStatus(200);
        $response->assertSee($player->full_name_ar);
        $response->assertSee($player->passport_number);
    }

    public function test_player_api_returns_json_verification(): void
    {
        $this->seed();

        $player = Player::first();

        $response = $this->getJson('/api/v1/players/' . $player->uuid);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'uuid' => $player->uuid,
                    'passport_number' => $player->passport_number,
                    'name_ar' => $player->full_name_ar,
                ],
            ]);
    }

    public function test_competitions_page_renders_with_divisions_and_categories(): void
    {
        $this->seed();

        $response = $this->get('/competitions?division=elite&category=U20');
        $response->assertStatus(200);
        $response->assertSee('بطولة النخبة');
        $response->assertSee('U20');
    }

    public function test_media_gallery_page_renders_correctly(): void
    {
        $this->seed();

        $response = $this->get('/media');
        $response->assertStatus(200);
        $response->assertSee('معرض الصور والفيديوهات');
    }
}
