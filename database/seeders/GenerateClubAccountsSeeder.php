<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GenerateClubAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = Club::where('is_active', true)->get();
        $this->command?->info("Generating accounts for {$clubs->count()} clubs...");

        $accounts = [];

        foreach ($clubs as $club) {
            $cleanCode = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $club->acronym ?: $club->short_name ?: 'club' . $club->id));
            if (empty($cleanCode)) {
                $cleanCode = 'club' . $club->id;
            }
            $email = "club.{$cleanCode}@lrf-blida.dz";

            // If email already exists for another club, make it unique
            $existing = User::where('email', $email)->where('club_id', '!=', $club->id)->first();
            if ($existing) {
                $email = "club.{$cleanCode}.{$club->id}@lrf-blida.dz";
            }

            $user = User::updateOrCreate(
                ['club_id' => $club->id],
                [
                    'name' => $club->name_ar,
                    'email' => $email,
                    'password' => Hash::make('Club@2026!'),
                    'role' => 'club',
                    'is_active' => true,
                    'phone' => $club->phone,
                ]
            );

            // Also update club's email field if empty
            if (empty($club->email)) {
                $club->update(['email' => $email]);
            }

            $accounts[] = [
                'club_id' => $club->id,
                'name_ar' => $club->name_ar,
                'name_fr' => $club->name_fr,
                'acronym' => $club->acronym,
                'email' => $email,
                'password' => 'Club@2026!',
            ];
        }

        // Save accounts list to storage / json for export
        file_put_contents(
            storage_path('app/club_accounts.json'),
            json_encode($accounts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $this->command?->info("Created/Updated " . count($accounts) . " club accounts.");
    }
}
