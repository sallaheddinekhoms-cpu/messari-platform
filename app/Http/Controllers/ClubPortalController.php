<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubTeam;
use App\Models\GameMatch;
use App\Models\GroupTeam;
use App\Models\Player;
use App\Models\PlayerLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubPortalController extends Controller
{
    /**
     * Show Club Login Page
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isClub()) {
            return redirect()->route('club.dashboard');
        }

        $sampleClubs = Club::where('is_active', true)->whereNotNull('email')->inRandomOrder()->limit(3)->get();
        return view('masari.club.login', compact('sampleClubs'));
    }

    /**
     * Authenticate Club
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->isClub() || !$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'هذا الحساب غير مفعل أو ليس حساباً معتمداً للأندية.',
                ]);
            }

            return redirect()->intended(route('club.dashboard'));
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة. يرجى التأكد من البريد الإلكتروني وكلمة المرور.',
        ])->onlyInput('email');
    }

    /**
     * Club Dashboard (U20, U17, U15 Categories Hub)
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isClub()) {
            return redirect()->route('club.login');
        }

        $club = $user->club;
        $activeTab = $request->query('tab', 'U20');
        if (!in_array($activeTab, ['U20', 'U17', 'U15'])) {
            $activeTab = 'U20';
        }

        // Load all qualified players for this club grouped by category
        $licenses = PlayerLicense::with(['player', 'ageCategory'])
            ->where('club_id', $club->id)
            ->whereHas('season', fn($q) => $q->where('is_current', true))
            ->get();

        $playersByCategory = [
            'U20' => collect(),
            'U17' => collect(),
            'U15' => collect(),
        ];

        foreach ($licenses as $lic) {
            $player = $lic->player;
            if (!$player) continue;

            $cat = strtoupper(trim($lic->ageCategory?->code ?: ''));
            if (isset($playersByCategory[$cat])) {
                $player->current_license_number = $lic->license_number;
                $player->category_code = $cat;
                $playersByCategory[$cat]->push($player);
            }
        }

        // Category Counts
        $counts = [
            'total' => $licenses->count(),
            'U20' => $playersByCategory['U20']->count(),
            'U17' => $playersByCategory['U17']->count(),
            'U15' => $playersByCategory['U15']->count(),
        ];

        // Competitions, Matches & Standings for each category via ClubTeam
        $competitionsByCategory = [];
        $matchesByCategory = [];
        $standingsByCategory = [];

        foreach (['U20', 'U17', 'U15'] as $cat) {
            $clubTeam = ClubTeam::where('club_id', $club->id)
                ->whereHas('ageCategory', fn($q) => $q->where('code', $cat))
                ->first();

            if ($clubTeam) {
                // Matches
                $matches = GameMatch::with(['homeTeam.club', 'awayTeam.club', 'stadium', 'round.group.competition'])
                    ->where(function ($q) use ($clubTeam) {
                        $q->where('home_team_id', $clubTeam->id)->orWhere('away_team_id', $clubTeam->id);
                    })
                    ->orderBy('match_date')
                    ->get();

                $matchesByCategory[$cat] = $matches;

                // Group & Competition
                $groupTeam = GroupTeam::with('group.competition')->where('club_team_id', $clubTeam->id)->first();
                $competitionsByCategory[$cat] = $groupTeam?->group?->competition;

                // Standings
                if ($groupTeam && $groupTeam->group) {
                    $standings = GroupTeam::with('clubTeam.club')
                        ->where('group_id', $groupTeam->group_id)
                        ->orderByDesc('points')
                        ->orderByDesc('goal_difference')
                        ->get();
                    $standingsByCategory[$cat] = $standings;
                } else {
                    $standingsByCategory[$cat] = collect();
                }
            } else {
                $matchesByCategory[$cat] = collect();
                $competitionsByCategory[$cat] = null;
                $standingsByCategory[$cat] = collect();
            }
        }

        // Next Upcoming Match across categories
        $clubTeamIds = ClubTeam::where('club_id', $club->id)->pluck('id');
        $nextMatch = GameMatch::with(['homeTeam.club', 'awayTeam.club', 'stadium', 'round.group.competition'])
            ->where(function ($q) use ($clubTeamIds) {
                $q->whereIn('home_team_id', $clubTeamIds)->orWhereIn('away_team_id', $clubTeamIds);
            })
            ->where('status', 'scheduled')
            ->orderBy('match_date')
            ->first();

        return view('masari.club.dashboard', compact(
            'club',
            'user',
            'activeTab',
            'counts',
            'playersByCategory',
            'competitionsByCategory',
            'matchesByCategory',
            'standingsByCategory',
            'nextMatch'
        ));
    }

    /**
     * Printable Official Roster Sheet with Photos for a Category
     */
    public function printRoster($category)
    {
        $user = Auth::user();
        if (!$user || !$user->isClub()) {
            return redirect()->route('club.login');
        }

        $club = $user->club;
        $category = strtoupper($category);
        if (!in_array($category, ['U20', 'U17', 'U15'])) {
            abort(404);
        }

        $licenses = PlayerLicense::with(['player', 'ageCategory'])
            ->where('club_id', $club->id)
            ->whereHas('season', fn($q) => $q->where('is_current', true))
            ->whereHas('ageCategory', fn($q) => $q->where('code', $category))
            ->get();

        $players = collect();
        foreach ($licenses as $lic) {
            $p = $lic->player;
            if ($p) {
                $p->license_number_display = $lic->license_number;
                $players->push($p);
            }
        }

        // Sort by position then name
        $players = $players->sortBy([
            ['primary_position', 'asc'],
            ['last_name_fr', 'asc'],
        ]);

        return view('masari.club.print_roster', compact('club', 'category', 'players'));
    }

    /**
     * Printable Official Match Sheet
     */
    public function printMatchSheet($matchId)
    {
        $user = Auth::user();
        if (!$user || !$user->isClub()) {
            return redirect()->route('club.login');
        }

        $club = $user->club;
        $match = GameMatch::with(['homeTeam.club', 'awayTeam.club', 'stadium', 'round.group.competition'])->findOrFail($matchId);

        $clubTeamIds = ClubTeam::where('club_id', $club->id)->pluck('id')->toArray();
        if (!in_array($match->home_team_id, $clubTeamIds) && !in_array($match->away_team_id, $clubTeamIds)) {
            abort(403, 'غير مصرح لك بطباعة ورقة مقابلة خاصة بنادٍ آخر.');
        }

        $ageCat = $match->homeTeam?->ageCategory ?? $match->awayTeam?->ageCategory;
        $category = $ageCat?->code ?? 'U17';

        // Load roster for home club
        $clubPlayers = Player::whereHas('licenses', function ($q) use ($club, $category) {
            $q->where('club_id', $club->id)
              ->whereHas('season', fn($s) => $s->where('is_current', true))
              ->whereHas('ageCategory', fn($a) => $a->where('code', $category));
        })->get();

        return view('masari.club.print_match_sheet', compact('club', 'match', 'category', 'clubPlayers'));
    }

    /**
     * Club Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('club.login');
    }
}
