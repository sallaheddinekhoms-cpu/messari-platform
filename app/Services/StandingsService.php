<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\Group;
use App\Models\GroupTeam;

class StandingsService
{
    /**
     * Recalculate standings for a specific group based on all finished matches.
     */
    public static function recalculateGroup(int $groupId): void
    {
        $group = Group::with(['groupTeams', 'rounds.matches'])->find($groupId);
        if (! $group) {
            return;
        }

        $groupTeams = $group->groupTeams->keyBy('club_team_id');

        // Reset stats
        $stats = [];
        foreach ($groupTeams as $teamId => $gt) {
            $stats[$teamId] = [
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0 - ($gt->penalty_deductions ?? 0),
                'form_matches' => [],
            ];
        }

        // Iterate through all matches of all rounds in this group
        $matches = GameMatch::whereHas('round', fn ($q) => $q->where('group_id', $groupId))
            ->where('status', 'finished')
            ->orderBy('match_date', 'asc')
            ->get();

        foreach ($matches as $match) {
            $homeId = $match->home_team_id;
            $awayId = $match->away_team_id;
            $hScore = (int) $match->home_score;
            $aScore = (int) $match->away_score;

            if (isset($stats[$homeId])) {
                $stats[$homeId]['played']++;
                $stats[$homeId]['goals_for'] += $hScore;
                $stats[$homeId]['goals_against'] += $aScore;

                if ($hScore > $aScore) {
                    $stats[$homeId]['won']++;
                    $stats[$homeId]['points'] += 3;
                    $stats[$homeId]['form_matches'][] = 'W';
                } elseif ($hScore === $aScore) {
                    $stats[$homeId]['drawn']++;
                    $stats[$homeId]['points'] += 1;
                    $stats[$homeId]['form_matches'][] = 'D';
                } else {
                    $stats[$homeId]['lost']++;
                    $stats[$homeId]['form_matches'][] = 'L';
                }
            }

            if (isset($stats[$awayId])) {
                $stats[$awayId]['played']++;
                $stats[$awayId]['goals_for'] += $aScore;
                $stats[$awayId]['goals_against'] += $hScore;

                if ($aScore > $hScore) {
                    $stats[$awayId]['won']++;
                    $stats[$awayId]['points'] += 3;
                    $stats[$awayId]['form_matches'][] = 'W';
                } elseif ($aScore === $hScore) {
                    $stats[$awayId]['drawn']++;
                    $stats[$awayId]['points'] += 1;
                    $stats[$awayId]['form_matches'][] = 'D';
                } else {
                    $stats[$awayId]['lost']++;
                    $stats[$awayId]['form_matches'][] = 'L';
                }
            }
        }

        // Sort by points, goal difference, goals for
        uasort($stats, function ($a, $b) {
            if ($b['points'] !== $a['points']) {
                return $b['points'] <=> $a['points'];
            }
            $diffA = $a['goals_for'] - $a['goals_against'];
            $diffB = $b['goals_for'] - $b['goals_against'];
            if ($diffB !== $diffA) {
                return $diffB <=> $diffA;
            }
            return $b['goals_for'] <=> $a['goals_for'];
        });

        // Persist to database
        $rank = 1;
        foreach ($stats as $teamId => $s) {
            $gt = $groupTeams[$teamId] ?? null;
            if ($gt) {
                $diff = $s['goals_for'] - $s['goals_against'];
                $form = implode('-', array_slice($s['form_matches'], -5));

                $gt->update([
                    'played' => $s['played'],
                    'won' => $s['won'],
                    'drawn' => $s['drawn'],
                    'lost' => $s['lost'],
                    'goals_for' => $s['goals_for'],
                    'goals_against' => $s['goals_against'],
                    'goal_difference' => $diff,
                    'points' => $s['points'],
                    'rank' => $rank,
                    'form' => $form ?: null,
                ]);
                $rank++;
            }
        }
    }
}
