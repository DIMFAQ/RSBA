<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$client = app(App\Services\DmsMiddlewareClient::class);
$prop = new ReflectionMethod($client, 'request');
$prop->setAccessible(true);
$http = $prop->invoke($client);

$res = $http->put('/displays/DSP005/mapping', [
    'target_type' => 'ward_summary',
    'target_id' => 'all'
]);
echo "Status: " . $res->status() . "\n";
echo "Body: " . $res->body() . "\n";
