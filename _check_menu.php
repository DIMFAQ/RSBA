<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$items = DB::table('menus')->where('parent_id', 53)->orderBy('id')->get(['id', 'nama']);
echo "=== Menu children of Penggajian (id=53) ===\n";
foreach ($items as $item) {
    echo "  ID {$item->id}: {$item->nama}\n";
}

$cols = DB::getSchemaBuilder()->getColumnListing('menus');
echo "\nColumns: " . implode(', ', $cols) . "\n";
