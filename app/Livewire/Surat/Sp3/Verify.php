<?php

namespace App\Livewire\Surat\Sp3;

use Livewire\Component;
use App\Models\SignatureCerts;
use App\Models\SignatureLogs;
use TallStackUi\Traits\Interactions;
use App\Models\Surat\SuratSp3Approval;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;

#[Lazy]
class Verify extends Component
{
    use Interactions;

    public string $signature;

    public $surat;
    public array $dataSuratAsli;
    public bool $verify;
    public $bgColor = 'white';

    public function render()
    {
        return view('livewire.surat.sp3.verify');
    }

    public function updatedSignature()
    {
        $this->bgColor = 'white';
        $this->verify = false;

        // Validate the signature
        $this->validate([
            'signature' => 'required|string',
        ]);


        $signature = $this->signature;
        $this->getSignature(signature: $signature);

        // reset input
        $this->signature = '';
    }

    private function getSignature(string $signature): void
    {
        $suratApproval = SuratSp3Approval::where('signature_hash', $signature)->latest('id')->first();



        if (!$suratApproval) {
            $this->toast()
                ->error('Invalid', 'Tidak ditemukan data.')
                ->send();
            return;
        }
        $this->surat = $suratApproval->surat;

        // get p12 based user approval
        $certs = SignatureCerts::where('user_id', $suratApproval->disetujui)->latest('id')->first();

        $dataToVerify  = json_encode([
            'surat_sp3_id' => $suratApproval->surat_sp3_id,
            'disetujui' => $suratApproval->disetujui,
            'status' => $suratApproval->status,
            'keterangan' => $suratApproval->keterangan ?? null,
            'approved_at' => $suratApproval->approved_at,
        ]);

        // verify signature
        $this->verify = $this->verifySignature(
            data: $dataToVerify,
            signature: $suratApproval->signature_hash,
            publicKey: $certs->public_key
        );

        //return data
        if ($this->verify) {
            $this->bgColor = 'green';
        } else {
            $this->bgColor = 'red';
        }
        // data surat yanga asli
        $this->dataSuratAsli = SignatureLogs::where('signature_hash', $signature)->get()
            ->map(function ($item) {
                $data = json_decode($item->data);
                // $data = $item->data;
                // dd($data, $data->surat_sp3_id);


                return [
                    'surat_sp3_id' => $data->surat_sp3_id,
                    'disetujui' => $data->disetujui,
                    'status' => $data->status,
                    'keterangan' => $data->keterangan ?? null,
                    'approved_at' => $data->approved_at,
                ];
            })->toArray();
    }

    private function verifySignature(string $data, string $signature, string $publicKey): bool
    {
        // Decode signature from base64
        $signatureBinary = base64_decode($signature);
        if ($signatureBinary === false) {
            throw new \Exception("Invalid signature encoding");
        }

        // Get public key resource
        $publicKeyResource = openssl_pkey_get_public($publicKey);
        if ($publicKeyResource === false) {
            throw new \Exception("Invalid public key");
        }

        // Verify signature
        $result = openssl_verify(
            $data,
            $signatureBinary,
            $publicKeyResource,
            OPENSSL_ALGO_SHA256
        );

        // Clean up
        openssl_free_key($publicKeyResource);

        // Handle result
        if ($result === 1) {
            return true;
        } elseif ($result === 0) {
            $debugInfo = [
                'input_data' => $data,
                'public_key' => $publicKey,
                'signature' => $signature,
                'openssl_error' => openssl_error_string()
            ];
            Log::error('Error verify data : ' . json_encode($debugInfo));

            return false;
        } else {
            throw new \Exception("Verification error: " . openssl_error_string());
        }
    }
}
