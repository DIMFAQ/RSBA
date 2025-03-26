<?php

namespace App\Livewire\Dashboard;

use GuzzleHttp\Client;
use Livewire\Attributes\Isolate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

#[Layout('components.layouts.dashboard')]
#[Lazy]
#[Isolate]
class Kamar extends Component
{
    use Interactions;

    public $kapasitas = [];
    public $loading = FALSE;

    function mount()
    {
        $this->getData();
    }

    function getData()
    {
        $this->loading = true;

        $client = new Client();

        $kode_fk = config('bpjs.kode_faskes');
        $CID = config('bpjs.cons_id');
        $secretKey = config('bpjs.secret_key');

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        // signature
        $signature = hash_hmac('sha256', $CID . "&" . $tStamp, $secretKey, true);
        $encodedSignature = base64_encode($signature);

        $header = [
            'User-Agent' => 'testing/1.0',
            'Accept' => 'application/json',
            'X-cons-id' => $CID,
            'X-timestamp' => $tStamp,
            'X-signature' => $encodedSignature,
        ];

        try {

            sleep(3);            # code...
            $res = $client->request(
                'GET',
                'https://new-api.bpjs-kesehatan.go.id' . '/aplicaresws/rest/bed/read/' . $kode_fk . '/1/20',
                [
                    'headers' => $header
                ]
            );

            // Get the body of the response
            $body = $res->getBody();
            $responseBody = json_decode($body, true); // Decode the JSON body into an array


            // group by kodekelas
            $groupByKelas = collect($responseBody['response']['list'])->sortBy('kodekelas')->groupBy('kodekelas');
            // dd($groupByKelas);

            $this->kapasitas = [];
            foreach ($groupByKelas as $kodekelas => $items) {
                $totalKapasitas = 0;
                $totalTersedia = 0;

                foreach ($items as $item) {
                    $totalKapasitas += $item['kapasitas'];
                    $totalTersedia += $item['tersedia'];

                    $terisi = $totalKapasitas - $totalTersedia;
                    $prosentase_terisi = round(($terisi / $totalKapasitas) * 100);
                }

                $color = 'green';
                if ($prosentase_terisi >= 50) {
                    $color = 'orange';
                } else if ($prosentase_terisi >= 85) {
                    $color = 'red';
                }

                // push data to $kapasitas array, to update in view
                $this->kapasitas[] =
                    [
                        'kelas' => $items[0]['namakelas'],
                        'kapasitas' => $totalKapasitas,
                        'tersedia' => $totalTersedia,
                        'prosentase' => $prosentase_terisi,
                        'prosentase_color' => $color
                    ];
            }
        } catch (\Throwable $e) {
            $this->toast()
                ->error('Failed', 'Error : ' . $e->getMessage())
                ->send();
        } finally {
            $this->loading = false;
        }
    }


    function placeholder()
    {
        return view('components.skeleton');
    }


    public function render()
    {
        return view('livewire.dashboard.kamar',);
    }
}
