<?php

namespace App\Livewire\Surat\Sp3;

use App\Models\SignatureLogs;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Lazy;
use App\Models\Surat\SuratSp3;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;
use App\Models\Surat\SuratSp3Approval;
use Illuminate\Support\Facades\Storage;

#[Lazy]
class Approval extends Component
{
    use Interactions;

    public ?SuratSp3 $suratSp3;

    public $optionsApproval = [
        ['value' => 'approved', 'label' => 'Setujui', 'color' => 'indigo'],
        ['value' => 'rejected', 'label' => 'Tolak', 'color' => 'red']
    ];

    public string $status = '', $keterangan;
    public string $password;

    public function rules(): array
    {
        return [
            'password' => 'required',
            'status' => 'required|string',
            'keterangan' => $this->status === 'rejected' ? 'required|string' : 'nullable|string'
        ];
    }

    public function mount($suratSp3)
    {
        $this->suratSp3 = $suratSp3;
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'surat_sp3_id' => $this->suratSp3->id,
            'disetujui' => auth()->user()->id,
            'status' => $this->status,
            'keterangan' => $this->keterangan ?? null,
            'approved_at' => now()->toIso8601String(),
        ];

        // ambil data signature user [p12 path]
        $certificate = auth()->user()->certificate()->latest('id')->first();

        // buat signature hash dari p12
        $dataToSign = json_encode($data);

        // buat hash signature dari data
        $hash = $this->createDigitalSignature($dataToSign, $certificate->p12_path);
        if ($hash) {

            $data['signature_hash'] = $hash; //adding hash to data

            DB::beginTransaction();
            try {
                SuratSp3Approval::create($data);
                $this->suratSp3->update(
                    ['status' => $this->status]
                );

                // insert to log signature
                $signatureLog = [
                    'data' => $dataToSign,
                    'signature_hash' => $hash,
                    'user_id' => auth()->user()->id,
                    'certificate_id' => $certificate->id
                ];
                SignatureLogs::create($signatureLog);

                DB::commit();
                $this->dispatch('update-approval');
                $this->dispatch('close-modal', id: 'modal-approval-sp3');

                $this->toast()
                    ->success('Berhasil.', "Surat cuti {$this->suratSp3->no} berhasil diupdate.")
                    ->send();
            } catch (\Throwable $e) {
                DB::rollback();

                $this->toast()
                    ->error('Tidak Berhasil.', "Error : {$e->getMessage()}")
                    ->send();
            }
        }
    }

    private function createDigitalSignature($data, $certificate)
    {

        $p12File = Storage::disk('certs')->path($certificate);
        if (!file_exists($p12File)) {
            $this->toast()
                ->error('Tidak Berhasil.', "Certificate tidak ditemukan.")
                ->send();
            return;
            // throw new \Exception("Certificate file not found");
        }

        // Baca sertifikat
        $certs = [];
        $pkcs12 = file_get_contents($p12File);

        if (!openssl_pkcs12_read($pkcs12, $certs, $this->password)) {
            // throw new \Exception("Failed to read PKCS#12 certificate");
            $this->toast()
                ->error('Tidak Berhasil.', "Password certificate tidak valid.")
                ->send();
            return;
        }

        // Buat signature
        $privateKey = $certs['pkey'];
        $success = openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        if (!$success) {
            // throw new \Exception("Failed to create digital signature");            
            $this->toast()
                ->error('Tidak Berhasil.', "Failed to create digital signature")
                ->send();
            return;
        }

        return base64_encode($signature);
    }


    public function render()
    {
        return view('livewire.surat.sp3.approval');
    }
}
