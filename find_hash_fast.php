<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hash = '2f2064f95873e9e50f0fcf9e5871a58b';
echo "SEARCHING HASH {$hash} IN RSBADEV DATABASE...\n";

$tables = ['surat_cuti', 'surat_cuti_approval', 'surat_sp3', 'surat_sp3_approval', 'surat_balasan_pkl', 'surat_balasan_penelitian', 'surat_perintah_tugas', 'signature_logs'];

foreach ($tables as $t) {
    if (!Schema::hasTable($t)) continue;
    $cols = Schema::getColumnListing($t);
    foreach ($cols as $col) {
        try {
            $count = DB::table($t)->where($col, $hash)->count();
            if ($count > 0) {
                echo "FOUND IN TABLE [{$t}] COLUMN [{$col}]: {$count} record(s)\n";
                $row = DB::table($t)->where($col, $hash)->first();
                print_r($row);
            }
        } catch (\Throwable $e) {}
    }
}

echo "SEARCH DONE.\n";
