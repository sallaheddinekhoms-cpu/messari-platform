<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MasariInitialSeeder::class,
        ]);

        \Illuminate\Support\Facades\Artisan::call('messari:populate-full-system');
    }
}
