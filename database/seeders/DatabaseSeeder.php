<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

require_once __DIR__ . '/OfficialCalendarSeeder.php';

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MasariInitialSeeder::class,
            OfficialCalendarSeeder::class,
        ]);

        \Illuminate\Support\Facades\Artisan::call('messari:populate-full-system');
    }
}
