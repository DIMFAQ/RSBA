<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pkl = App\Models\Surat\SuratBalasanPkl::latest()->first();
if (!$pkl) {
    echo "No SuratBalasanPkl found\n";
    exit;
}

echo "Found PKL ID: " . $pkl->id . ", No: " . $pkl->no . ", Current Key: " . ($pkl->docstore_key ?: 'NULL') . "\n";

$syncService = app(App\Services\DocstoreSyncService::class);
$res = $syncService->syncBalasanPkl($pkl);

echo "Sync result: " . ($res ? 'SUCCESS' : 'FAILED') . "\n";
echo "Docstore Key after sync: " . ($pkl->fresh()->docstore_key ?: 'NULL') . "\n";
