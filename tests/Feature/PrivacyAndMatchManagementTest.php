<?php

namespace Tests\Feature;

use App\Models\GameMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\User;
use Tests\TestCase;

class PrivacyAndMatchManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'masari_db',
        ]);
    }
    public function test_guest_cannot_view_full_player_passport(): void
    {
        $player = Player::first();
        if (!$player) {
            $this->markTestSkipped('No players in database');
        }

        $response = $this->get('/passport/' . $player->uuid);
        $response->assertStatus(200);
        $response->assertSee('الملف الرياضي والتقني محمي بالاعتماد الرسمي');
        $response->assertSee('فضاء تقني مقيّد');
    }

    public function test_dtr_and_admin_can_view_player_passport(): void
    {
        $player = Player::first();
        $admin = User::where('role', 'admin')->first();
        if (!$admin || !$player) {
            $this->markTestSkipped('No admin or player found');
        }

        $response = $this->actingAs($admin)->get('/passport/' . $player->uuid);
        $response->assertStatus(200);
        $response->assertSee($player->full_name_ar);
    }

    public function test_guest_search_is_restricted(): void
    {
        $response = $this->get('/search?q=test');
        $response->assertStatus(200);
        $response->assertSee('البحث في قاعدة بيانات اللاعبين مقيّد بالاعتماد التقني');
    }

    public function test_match_can_record_scores_and_events(): void
    {
        $match = GameMatch::first();
        $player = Player::first();
        if (!$match || !$player) {
            $this->markTestSkipped('No match or player found');
        }

        $match->update([
            'home_score' => 3,
            'away_score' => 2,
            'status' => 'finished',
            'is_validated' => true,
        ]);

        $this->assertEquals(3, $match->fresh()->home_score);
        $this->assertEquals('finished', $match->fresh()->status);

        $event = MatchEvent::create([
            'match_id' => $match->id,
            'club_team_id' => $match->home_team_id,
            'player_id' => $player->id,
            'event_type' => 'goal',
            'minute' => 55,
        ]);

        $this->assertDatabaseHas('match_events', [
            'id' => $event->id,
            'event_type' => 'goal',
            'minute' => 55,
        ]);
    }

    public function test_homepage_is_clean_and_shows_no_player_passports_or_restricted_banner(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('اللجنة التقنية الجهوية');
        $response->assertSee('مركز البطولات والرزنامة');
        $response->assertDontSee('مواهب الأسبوع');
        $response->assertDontSee('فضاء تقني مقيّد');
    }

    public function test_dtr_page_loads_successfully(): void
    {
        $response = $this->get('/dtr');
        $response->assertStatus(200);
        $response->assertSee('المنظومة الرقمية لتكوين المدربين');
        $response->assertSee('المديرية واللجنة التقنية الجهوية');
    }
}
