<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\JsonResponse;

class PlayerApiController extends Controller
{
    public function getPlayerByUuid(string $uuid): JsonResponse
    {
        $player = Player::with([
            'wilaya',
            'currentLicense.club',
            'currentLicense.ageCategory',
            'careerTimelines.club',
            'careerTimelines.season',
            'careerTimelines.ageCategory',
        ])->where('uuid', $uuid)->first();

        if (! $player) {
            return response()->json([
                'success' => false,
                'message' => 'اللاعب غير مسجل أو رمز الجواز غير صالح',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'uuid' => $player->uuid,
                'passport_number' => $player->passport_number,
                'name_ar' => $player->full_name_ar,
                'name_fr' => $player->full_name_fr,
                'birth_date' => $player->birth_date?->format('Y-m-d'),
                'age' => $player->age,
                'birth_place' => $player->birth_place,
                'position' => $player->primary_position,
                'dominant_foot' => $player->dominant_foot,
                'blood_type' => $player->blood_type,
                'height_cm' => $player->height_cm,
                'weight_kg' => $player->weight_kg,
                'current_club' => [
                    'name' => $player->currentLicense?->club?->name_ar,
                    'acronym' => $player->currentLicense?->club?->acronym,
                ],
                'age_category' => $player->currentLicense?->ageCategory?->code,
                'status' => $player->status,
                'verified' => true,
                'verification_url' => route('passport.show', $player->uuid),
            ],
        ]);
    }
}
