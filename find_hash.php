<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hash = 'f44b7c6f2616e983d4edca3c2bbffeda';
$out = [];
$out[] = "Searching hash: " . $hash;

$cutiApps = \DB::table('surat_cuti_approval')->where('qr_verification_hash', $hash)->orWhere('signature_hash', $hash)->get();
$out[] = "Found CutiApproval: " . count($cutiApps);
foreach ($cutiApps as $c) { $out[] = json_encode($c); }

$sp3Apps = \DB::table('surat_sp3_approval')->where('qr_verification_hash', $hash)->orWhere('signature_hash', $hash)->get();
$out[] = "Found Sp3Approval: " . count($sp3Apps);
foreach ($sp3Apps as $s) { $out[] = json_encode($s); }

$pkls = \DB::table('surat_balasan_pkl')->where('qr_verification_hash', $hash)->orWhere('docstore_key', $hash)->get();
$out[] = "Found PKL: " . count($pkls);
foreach ($pkls as $p) { $out[] = json_encode($p); }

$penelitians = \DB::table('surat_balasan_penelitian')->where('qr_verification_hash', $hash)->orWhere('docstore_key', $hash)->get();
$out[] = "Found Penelitian: " . count($penelitians);
foreach ($penelitians as $pen) { $out[] = json_encode($pen); }

$spts = \DB::table('surat_perintah_tugas')->where('qr_verification_hash', $hash)->orWhere('docstore_key', $hash)->get();
$out[] = "Found SPT: " . count($spts);
foreach ($spts as $spt) { $out[] = json_encode($spt); }

$logs = \DB::table('signature_logs')->where('signature_hash', $hash)->orWhere('data_hash', $hash)->get();
$out[] = "Found SignatureLogs: " . count($logs);
foreach ($logs as $l) { $out[] = json_encode($l); }

$out[] = "\n--- LATEST SURAT RECORDS ---";
$latestPkl = \DB::table('surat_balasan_pkl')->latest('id')->first();
if ($latestPkl) $out[] = "Latest PKL: ID={$latestPkl->id}, docstore_key=" . ($latestPkl->docstore_key ?? 'NULL') . ", qr_hash=" . ($latestPkl->qr_verification_hash ?? 'NULL');

$latestCuti = \DB::table('surat_cuti')->latest('id')->first();
if ($latestCuti) $out[] = "Latest Cuti: ID={$latestCuti->id}, docstore_key=" . ($latestCuti->docstore_key ?? 'NULL');

$latestSp3 = \DB::table('surat_sp3')->latest('id')->first();
if ($latestSp3) $out[] = "Latest SP3: ID={$latestSp3->id}, docstore_key=" . ($latestSp3->docstore_key ?? 'NULL');

$latestSpt = \DB::table('surat_perintah_tugas')->latest('id')->first();
if ($latestSpt) $out[] = "Latest SPT: ID={$latestSpt->id}, docstore_key=" . ($latestSpt->docstore_key ?? 'NULL');

file_put_contents(__DIR__ . '/find_hash_out.txt', implode("\n", $out));
echo "DONE";
