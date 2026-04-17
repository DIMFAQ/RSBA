<?php

namespace App\Livewire\Jasmed;

use App\Exports\RanapExport;
use App\Exports\TemplateImportJasa;
use App\Imports\RanapImport;
use App\Models\JmPasien;
use App\Services\JasaMedisBpjsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use TallStackUi\Traits\Interactions;
use Throwable;

#[Lazy(isolate: false)]
class BpjsRanap extends Component
{
    use WithFileUploads;
    use Interactions;

    public $pilih_download_ranap;

    public $bulan_ri;
    public $batch_ri;

    protected JasaMedisBpjsService $jasaMedisBpjsService;

    public function boot(JasaMedisBpjsService $jasaMedisBpjsService)
    {
        $this->jasaMedisBpjsService = $jasaMedisBpjsService;
    }

    public $excelImportKelompokRanap;

    function importKelompokRanap()
    {
        $this->validateOnly('excelImportKelompokRanap', ['excelImportKelompokRanap' => 'required|mimes:xlsx,xls']);

        $file = $this->excelImportKelompokRanap->store('excelImportKelompokRanap');
        try {
            Excel::import(new RanapImport(), $file);

            $this->toast()
                ->success(
                    'Sukses!',
                    'Upload data sukses.!'
                )->send();
        } catch (Throwable $e) {
            $errors = $e->getMessage();
            $this->toast()
                ->error(
                    'Gagal !',
                    "Errror : $errors"
                )->send();
        }
    }


    private function getPasien($tahun, $bulan, $batch): Builder
    {
        return JmPasien::whereYear('tgl_checkout', $tahun)
            ->whereMonth('tgl_checkout', $bulan)
            ->where('disetujui', '>', 0)
            ->where('layanan', 'ranap')
            ->where('cabar', 'bpjs')
            ->where('batch', $batch)
            ->whereNotNull('kelompok');
        // ->get();
    }

    /** 
     * HITUNG JASA RAWAT INAP BPJS
     */
    // function sumbitProcessRanap()
    // {
    //     $this->validate(['bulan_ri' => 'required', 'batch_ri' => 'required']);

    //     // split tahun bulan
    //     [$tahun, $bulan] = explode('-', $this->bulan_ri);

    //     // get pasien data
    //     $pasiens = $this->getPasien(tahun: $tahun, bulan: $bulan, batch: $this->batch_ri);

    //     // each by pasien
    //     $hasError = false;
    //     foreach ($pasiens as $pasien) {
    //         // try hitung dan rekap
    //         try {

    //             // Hitung
    //             $this->jasaMedisBpjsService->processPasien($pasien);
    //         } catch (Throwable $e) {
    //             $errors = $e->getMessage();

    //             // toast
    //             $this->toast()->error('Gagal !', "Error : " . $errors)->send();
    //             $hasError = true;
    //             continue;
    //         }
    //     }

    //     // toast
    //     if (!$hasError) {
    //         # code...
    //         $this->toast()->success('Berhasil !', 'Proses hitung jasa selesai.!')->send();
    //     }
    // }
    // New Version
    public function sumbitProcessRanap()
    {
        $this->validate(['bulan_ri' => 'required', 'batch_ri' => 'required']);

        //     // split tahun bulan
        [$tahun, $bulan] = explode('-', $this->bulan_ri);

        //     // get pasien data
        $query = $this->getPasien(tahun: $tahun, bulan: $bulan, batch: $this->batch_ri);

        $totalPatients = $query->count();
        $processed = 0;
        $errors = [];

        // proses dalam chunk untuk manage memory
        $query->chunk(100, function ($pasiens) use (&$processed, &$errors) {
            foreach ($pasiens as $pasien) {
                try {
                    $this->jasaMedisBpjsService->processPasien($pasien);
                    $processed++;
                } catch (\Throwable $e) {
                    $errors[] = [
                        'pasien_id' => $pasien->id,
                        'nama'       => $pasien->nama_pasien ?? 'N/A',
                        'error'      => $e->getMessage(),
                    ];
                }
            }
        });

        if (empty($errors)) {
            $this->toast()->success(
                'Selesai',
                "Berhasil! $processed pasien diproses."
            )->send();
        } else {
            $errorCount = count($errors);
            $this->toast()->error(
                'Tidak Selesai',
                "Selesai dengan $errorCount error. $processed pasien berhasil."
            );

            // Optionally log errors for review
            Log::error('Jasa Medis Processing Errors', ['errors' => $errors]);
        }
    }

    // Download hasil rekap ranap
    public function downloadRanap()
    {
        $this->validate(['bulan_ri' => 'required', 'batch_ri' => 'required']);

        // $periode = $this->bulan_ri;
        // $kelompok = $this->pilih_download_ranap;
        // $batch = $this->batch_ri;

        return Excel::download(
            new RanapExport(
                periode: $this->bulan_ri,
                cabar: 'bpjs',
                kelompok: $this->pilih_download_ranap,
                batch: $this->batch_ri
            ),
            'Rekap Jasa Ranap BPJS ' . $this->bulan_ri . '.xlsx'
        );

        $this->toast()->success('Sukses !', 'Download berhasil.')->send();
    }

    function downloadTemplate($template)
    {
        return Excel::download(
            new TemplateImportJasa($template),
            'Template Import Kelompok Rawat Inap.xlsx'
        );
    }



    public function render()
    {
        return view('livewire.jasmed.bpjs-ranap');
    }
}
