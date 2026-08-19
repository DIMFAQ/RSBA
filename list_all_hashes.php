<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$out = [];
$out[] = "=== LIST ALL HASHES IN OFFICE DATABASE ===";

$out[] = "1. SuratPerintahTugas:";
foreach (App\Models\Surat\SuratPerintahTugas::all() as $spt) {
    $out[] = "   ID={$spt->id}, No={$spt->no}, Status={$spt->status}, QR_Hash={$spt->qr_verification_hash}, DocstoreKey={$spt->docstore_key}";
}

$out[] = "2. SuratBalasanPkl:";
foreach (App\Models\Surat\SuratBalasanPkl::all() as $pkl) {
    $out[] = "   ID={$pkl->id}, No={$pkl->no}, Status={$pkl->status}, QR_Hash={$pkl->qr_verification_hash}, DocstoreKey={$pkl->docstore_key}";
}

$out[] = "3. SuratBalasanPenelitian:";
foreach (App\Models\Surat\SuratBalasanPenelitian::all() as $p) {
    $out[] = "   ID={$p->id}, No={$p->no}, Status={$p->status}, QR_Hash={$p->qr_verification_hash}, DocstoreKey={$p->docstore_key}";
}

$out[] = "4. SuratCutiApproval:";
foreach (App\Models\Surat\SuratCutiApproval::all() as $c) {
    $out[] = "   ID={$c->id}, SuratCutiID={$c->surat_cuti_id}, QR_Hash={$c->qr_verification_hash}, SigHash={$c->signature_hash}";
}

$out[] = "5. SuratSp3Approval:";
foreach (App\Models\Surat\SuratSp3Approval::all() as $s) {
    $out[] = "   ID={$s->id}, SuratSp3ID={$s->surat_sp3_id}, QR_Hash={$s->qr_verification_hash}, SigHash={$s->signature_hash}";
}

$out[] = "6. SignatureLogs (latest 10):";
foreach (App\Models\SignatureLogs::latest('id')->take(10)->get() as $log) {
    $out[] = "   ID={$log->id}, Type={$log->sign_type}, SignID={$log->sign_id}, DataHash={$log->data_hash}";
}

file_put_contents(__DIR__ . '/hashes_out.txt', implode("\n", $out));
echo "DONE LISTING HASHES";
