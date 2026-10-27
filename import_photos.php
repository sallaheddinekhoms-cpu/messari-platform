<?php
ini_set('memory_limit', '2048M');
set_time_limit(0);

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

$jsonFile = 'c:\\Users\\Gtech2023\\Downloads\\players_photos (2).json';

if (!file_exists($jsonFile)) {
    echo "File not found: {$jsonFile}\n";
    exit(1);
}

echo "File exists! Size: " . round(filesize($jsonFile) / (1024 * 1024), 2) . " MB\n";
echo "Loading JSON into memory...\n";

$data = json_decode(file_get_contents($jsonFile), true);
if (!is_array($data)) {
    echo "JSON decode failed: " . json_last_error_msg() . "\n";
    exit(1);
}

$total = count($data);
echo "Total photos in JSON: {$total}\n";

$targetDir = public_path('images/players');
if (!File::exists($targetDir)) {
    File::makeDirectory($targetDir, 0755, true);
}

// Preload all licenses in DB for instant lookup
echo "Preloading database licenses...\n";
$licenses = DB::table('player_licenses')
    ->select('license_number', 'player_id')
    ->get()
    ->keyBy('license_number');

echo "Found " . $licenses->count() . " player licenses in database.\n";

$saved = 0;
$linked = 0;
$skipped = 0;
$i = 0;

$playersToUpdate = [];

foreach ($data as $licNum => $base64) {
    $i++;
    $licNum = trim($licNum);

    if (empty($base64) || strlen($base64) < 100) {
        $skipped++;
        continue;
    }

    if (preg_match('/^data:image\/[a-z]+;base64,(.+)$/s', $base64, $m)) {
        $base64 = $m[1];
    }

    $raw = base64_decode($base64);
    if (!$raw) {
        $skipped++;
        continue;
    }

    $filename = $licNum . '.jpg';
    $savePath = $targetDir . '/' . $filename;
    file_put_contents($savePath, $raw);
    $saved++;

    if (isset($licenses[$licNum])) {
        $playerId = $licenses[$licNum]->player_id;
        $playersToUpdate[$playerId] = 'images/players/' . $filename;
        $linked++;
    }

    if ($i % 500 === 0) {
        echo "Processed {$i}/{$total} photos... (Saved: {$saved}, Linked: {$linked})\n";
    }
}

echo "Updating database records in chunks...\n";
$chunks = array_chunk($playersToUpdate, 500, true);
foreach ($chunks as $chunk) {
    DB::transaction(function () use ($chunk) {
        foreach ($chunk as $playerId => $photoPath) {
            DB::table('players')->where('id', $playerId)->update([
                'photo_path' => $photoPath,
                'updated_at' => now(),
            ]);
        }
    });
}

echo "\n=============================================\n";
echo "🎉 IMPORT COMPLETED SUCCESSFULLY!\n";
echo "Total in JSON: {$total}\n";
echo "Saved to disk: {$saved} photos\n";
echo "Linked to DB:  {$linked} players updated\n";
echo "Skipped:       {$skipped}\n";
echo "Target folder: {$targetDir}\n";
echo "=============================================\n";
