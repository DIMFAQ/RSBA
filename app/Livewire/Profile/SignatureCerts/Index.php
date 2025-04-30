<?php

namespace App\Livewire\Profile\SignatureCerts;

use App\Models\SignatureCerts;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use TallStackUi\Traits\Interactions;
use Livewire\Attributes\Computed;

#[Lazy]
class Index extends Component
{
    use Interactions;

    public User $users;

    public string $pkcs12_password;
    public bool $modalPassw = false;

    public $rules = [
        'pkcs12_password' => 'required|string'
    ];

    public function mount()
    {
        $this->users = auth()->user();
    }

    #[Computed]
    function getCertificate()
    {
        $data = SignatureCerts::where('user_id', $this->users->id)
            ->orderByDesc('id')
            ->first();

        if ($data) {
            return [
                'cert_info' => json_decode($data->cert_info),
                'p12_path' => $data->p12_path,
                'is_active' => $data->is_active,
                'expired_at' => $data->expired_at
            ];
        }
    }

    // public function parseCertificate()
    // {
    //     $this->validate();

    //     $certificates = $this->getCertificate();

    //     $pkcs12Path = Storage::disk('certs')->path($certificates['p12_path']);
    //     $pkcs12 = file_get_contents($pkcs12Path);

    //     try {
    //         $certs = [];
    //         if (openssl_pkcs12_read($pkcs12, $certs, $this->pkcs12_password)) {
    //             $this->modalPassw = false;
    //             // Access parts of the certificate
    //             // $privateKey = $certs['pkey'] ?? null;
    //             $certificate = $certs['cert'];

    //             // $extraCerts = $certs['extracerts'] ?? null;

    //             // Get certificate details
    //             $certDetails = openssl_x509_parse($certificate);
    //             $now = time();
    //             $isValid = $now >= $certDetails['validFrom_time_t'] && $now <= $certDetails['validTo_time_t'];

    //             // return
    //             return $this->certificate = [
    //                 'certDetails' => $certDetails,
    //                 'key' => $certs['pkey'],
    //                 'isValid' => $isValid
    //             ];
    //         } else {
    //             throw new \Exception("Invalid password");
    //         }
    //     } catch (\Throwable $e) {
    //         $this->toast()
    //             ->error('Invalid', $e->getMessage())
    //             ->send();
    //     }
    // }

    public function render()
    {
        return view('livewire.profile.signature-certs.index');
    }
}
