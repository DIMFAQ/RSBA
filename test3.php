<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$client = app(App\Services\DmsMiddlewareClient::class);
echo "1. Getting displays...\n";
$displays = $client->getDisplays();
$dev = collect($displays)->firstWhere('display_id', 'DSP005');
echo "Current mapping: " . json_encode($dev['mappings']) . "\n";

echo "2. Updating mapping to ward_class VIP...\n";
$prop = new ReflectionMethod($client, 'request');
$prop->setAccessible(true);
$http = $prop->invoke($client);

$res = $http->put('/displays/' . $dev['id'] . '/mapping', [
    'target_type' => 'ward_class',
    'target_id' => '019f4b01-458f-700c-95c5-3179ea63196f'
]);
echo "Status: " . $res->status() . "\n";
echo "Body: " . $res->body() . "\n";

echo "3. Getting displays again...\n";
$displays = $client->getDisplays();
$dev = collect($displays)->firstWhere('display_id', 'DSP005');
echo "New mapping: " . json_encode($dev['mappings']) . "\n";
