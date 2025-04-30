<?php

namespace App\Livewire\Profile\SignatureCerts;

use App\Models\SignatureCerts;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Lazy;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

#[Lazy]
class Add extends Component
{
    // use WithFileUploads;
    use Interactions;

    public $users;
    public string $org = 'RSBA', $org_unit, $nama, $email;
    public string $password, $passwordConfirmation;

    // storage path untuk certificate
    protected $relativePath;

    public $rules = [
        'org_unit' => 'required',
        'nama' => 'required',
        'email' => 'required',
        'password' => 'required|same:passwordConfirmation',

    ];

    public function mount(?User $users)
    {
        $this->nama  = $users->karyawan->nama;
        $this->email = $users->email;

        $this->relativePath = $users->id;
    }


    public function submit()
    {
        $this->validate();

        $this->generateCerts();
    }

    private function generateCerts()
    {
        $exp_days = 1095; // 3tahun

        $subject = "/C=ID/ST=Lampung/L=Bandar Lampung/O={$this->org}/OU={$this->org_unit}/CN={$this->nama}/emailAddress={$this->email}";

        // Buat direktori temporary
        $tempDir = storage_path('app/temp/' . Str::random(16));
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }
        // Temporary file certificate
        $privateKeyPath = $tempDir . '/private.key';
        $csrPath = $tempDir . '/request.csr';
        $certPath = $tempDir . '/certificate.crt';
        $p12Path = $tempDir . '/certificate.p12';

        // Generate private key
        shell_exec("openssl genrsa -out {$privateKeyPath} 2048");

        // Generate CSR
        shell_exec("openssl req -new -key {$privateKeyPath} -out {$csrPath} -subj '{$subject}'");

        // Self-sign the certificate (untuk contoh)
        shell_exec("openssl x509 -req -days {$exp_days} -in {$csrPath} -signkey {$privateKeyPath} -out {$certPath}");

        // Create PKCS#12 file
        shell_exec("openssl pkcs12 -export -out {$p12Path} -inkey {$privateKeyPath} -in {$certPath} -password pass:{$this->password}");


        if (file_exists($p12Path)) {
            // Simpan ke storage
            // $finalPath = "certificates/{$this->users->id}/certificate.p12";
            // Storage::disk('certs')->put($finalPath, file_get_contents($p12Path));

            $relativePath = $this->users->id;
            $finalPathP12 = $relativePath . '/p12/certificate-' . time() . '.p12';

            // Save files using Storage facade (handles paths automatically)
            Storage::disk('certs')->put($relativePath . '/private.key', file_get_contents($privateKeyPath));
            Storage::disk('certs')->put($relativePath . '/certificate.crt', file_get_contents($certPath));
            Storage::disk('certs')->put($finalPathP12, file_get_contents($p12Path));

            // parse p12 data
            $certs = $this->parseCertificate(p12: $finalPathP12, pass: $this->password);

            // simpan p12 data ke databse 
            SignatureCerts::create(
                [
                    'user_id' => $this->users->id,
                    'public_key' => $certs['public_key'],
                    'cert_info' => $certs['cert_info'],
                    'p12_path' => $certs['p12_path'],
                    'expired_at' => $certs['expired_at'],
                ]
            );

            // Hapus file temporary
            File::deleteDirectory($tempDir);

            $this->toast()
                ->success('Berhasil', 'Digital signature berhasil digenerate.')
                ->send();
        } else {
            $this->toast()
                ->error('Tidak Berhasil', 'Gagal generate digital signature.')
                ->send();
        }
    }

    // Parse data .P12
    private function parseCertificate($p12, $pass)
    {
        $pkcs12Path = Storage::disk('certs')->path($p12);
        $pkcs12 = file_get_contents($pkcs12Path);
        $certs = [];
        if (openssl_pkcs12_read($pkcs12, $certs, $pass)) {
            // Ambil data dari sertifikat
            $certData = openssl_x509_parse($certs['cert']);

            // ambil data public key
            $publicKey = openssl_pkey_get_details(openssl_pkey_get_public($certs['cert']))['key'];

            // ambil expired date
            $expiredAt = isset($certData['validTo_time_t'])
                ? Carbon::createFromTimestamp($certData['validTo_time_t'])
                : null;

            return [
                'public_key' => $publicKey,
                'cert_info' => json_encode($certData),
                'p12_path' => $p12,
                'expired_at' => $expiredAt,
            ];
        } else {
            // Gagal membaca p12
            throw new \Exception("Gagal membaca file .p12, mungkin password salah?");
        }
    }

    public function render()
    {
        return view('livewire.profile.signature-certs.add');
    }
}
