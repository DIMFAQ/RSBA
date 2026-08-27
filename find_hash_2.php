<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hash = '2f2064f95873e9e50f0fcf9e5871a58b';
echo "=== GLOBAL SEARCH FOR HASH: {$hash} ===\n";

// Search in office (rsbadev)
$tables = DB::select("SHOW TABLES");
foreach ($tables as $t) {
    $tableName = current((array)$t);
    $cols = Schema::getColumnListing($tableName);
    foreach ($cols as $col) {
        try {
            $count = DB::table($tableName)->where($col, $hash)->count();
            if ($count > 0) {
                echo "FOUND in office DB -> Table [{$tableName}], Column [{$col}]: {$count} row(s)\n";
                $row = DB::table($tableName)->where($col, $hash)->first();
                print_r($row);
            }
        } catch (\Throwable $e) {}
    }
}

// Search in docstore
try {
    $docstoreTables = DB::connection('docstore')->select("SHOW TABLES");
    foreach ($docstoreTables as $t) {
        $tableName = current((array)$t);
        $cols = Schema::connection('docstore')->getColumnListing($tableName);
        foreach ($cols as $col) {
            try {
                $count = DB::connection('docstore')->table($tableName)->where($col, $hash)->count();
                if ($count > 0) {
                    echo "FOUND in docstore DB -> Table [{$tableName}], Column [{$col}]: {$count} row(s)\n";
                    $row = DB::connection('docstore')->table($tableName)->where($col, $hash)->first();
                    print_r($row);
                }
            } catch (\Throwable $e) {}
        }
    }
} catch (\Throwable $e) {
    echo "Docstore DB connection error: " . $e->getMessage() . "\n";
}
