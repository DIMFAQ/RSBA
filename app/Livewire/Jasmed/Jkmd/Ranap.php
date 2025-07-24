<?php

namespace App\Livewire\Jasmed\Jkmd;

use App\Models\JmJasa;
use Livewire\Component;
use App\Models\JmDokter;
use App\Models\JmPasien;
use App\Models\JmRincian;
use App\Exports\RanapExport;
use App\Imports\RanapImport;
use App\Models\JmProsentase;
use Livewire\Attributes\Lazy;
use Livewire\WithFileUploads;
use App\Exports\TemplateImportJasa;
use Maatwebsite\Excel\Facades\Excel;
use TallStackUi\Traits\Interactions;

#[Lazy]
class Ranap extends Component
{
    use WithFileUploads;
    use Interactions;

    public $pilih_download_ranap;

    public $bulan_ri;
    public $batch_ri;

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
        } catch (\Throwable $e) {
            $errors = $e->getMessage();
            $this->toast()
                ->error(
                    'Gagal !',
                    "Errror : $errors"
                )->send();
        }
    }


    private function getPasien($tahun, $bulan, $batch)
    {
        return JmPasien::whereYear('tgl_checkout', $tahun)
            ->whereMonth('tgl_checkout', $bulan)
            ->where('disetujui', '>', 0)
            ->where('layanan', 'ranap')
            ->where('cabar', 'jkmd')
            ->where('batch', $batch)
            ->whereNotNull('kelompok')
            ->get();
    }


    private function getDokter($pasien)
    {
        $dokters = JmDokter::where('jm_pasien_id', $pasien->id)->get();

        // ada dpjp ?
        $hasDpjp = $dokters->contains('status', 'dpjp');

        // jika tidak ada
        if (!$hasDpjp) {
            // add pasien->dpjp ke colection JmDokter
            $defaultDokter = new JmDokter([
                'dokter' => $pasien->dpjp,
                'status' => 'dpjp',
                'jumlah' => 1,
            ]);
            // push data nya ke Collection JmDokter
            $dokters->push($defaultDokter);
        }

        return $dokters;
    }


    // get total riil rumah sakit
    private function getRincian($pasien)
    {
        $rincian = JmRincian::where('jm_pasien_id', $pasien->id)->first();

        $tarif_rs = $pasien->tarif_rs;
        $total_to_no = $rincian->prosedur_bedah + $rincian->prosedur_non_bedah;
        $real_rs = ($tarif_rs - $total_to_no) - (ceil($tarif_rs * 20) / 100);

        $data = [
            'riil_rs' =>  $real_rs,
            'chosaring' => $rincian->chosaring
        ];
        return json_decode(json_encode($data));
    }


    /** 
     * HITUNG JASA RAWAT INAP JKMD
     */
    function sumbitProcessRanap()
    {
        $this->validate(['bulan_ri' => 'required', 'batch_ri' => 'required']);

        // split tahun bulan
        [$tahun, $bulan] = explode('-', $this->bulan_ri);

        // get pasien data
        $pasiens = $this->getPasien(tahun: $tahun, bulan: $bulan, batch: $this->batch_ri);

        // each by pasien
        $hasError = false;
        foreach ($pasiens as $pasien) {
            // try hitung dan rekap
            try {
                $dokter = $this->getDokter($pasien);
                // prosentase Calc
                $prosentase = $this->calcProsentaseRanap($pasien);

                // kelompok jasa
                $kelompok = $pasien->kelompok;
                $this->calcJasaDokterRanap($prosentase, $dokter, $kelompok);
            } catch (\Throwable $e) {
                $errors = $e->getMessage();

                // toast
                $this->toast()->error('Gagal !', "Error : " . $errors)->send();
                $hasError = true;
                continue;
            }
        }

        // toast
        if (!$hasError) {
            # code...
            $this->toast()->success('Berhasil !', 'Proses hitung jasa selesai.!')->send();
        }
    }


    // kalkulasi perhitungan jasa ranap sesuai prosentase
    private function calcProsentaseRanap($pasien)
    {
        $rincian = $this->getRincian($pasien);

        // get total rincian
        $total_billing = $rincian->riil_rs;
        $chosaring = $rincian->chosaring;

        $klaim = $pasien->disetujui + $chosaring;
        // $jasa = ceil((($klaim + $chosaring) * 38) / 100);
        $jasa = ceil(($klaim * 38) / 100);
        $rincian = $total_billing + $jasa;
        $klaim_rincian = $klaim - $rincian;

        // jasa pelayanan 38%
        $j_38 = $jasa;
        if ($klaim_rincian < 0) {
            $j_38 = $jasa + $klaim_rincian;
        }

        // jasa rs
        $j_rs = ceil(($j_38 * 10) / 100);
        $j_medis = 0;
        $j_anastesi = 0;
        $j_penata = 0;
        $j_medis = 0;
        $j_resus = 0;
        $j_pekerja = 0;
        $j_sppdkgh = 0;
        $j_umum_s = 0;
        $j_dpjp_hd = 0;

        switch ($pasien->kelompok) {
            case 'ri_no':
                $j_medis = ceil(($j_38 * 36.21) / 100);
                $j_pekerja = ceil(($j_38 * 53.79) / 100);
                break;

            case 'ri_op':
                $j_medis = ceil(($j_38 * 59) / 100);
                $j_anastesi = ceil(($j_38 * 19.29) / 100);
                $j_penata = ceil(($j_38 * 5.4) / 100);
                $j_pekerja = ceil(($j_38 * 6.31) / 100);
                break;

            case 'ri_mata':
                $j_medis = ceil(($j_38 * 59.29) / 100);
                $j_anastesi = ceil(($j_38 * 18) / 100);
                $j_penata = ceil(($j_38 * 5.87) / 100);
                $j_pekerja = ceil(($j_38 * 6.85) / 100);
                break;

            case 'ri_partus':
                $j_medis  = ceil(($j_38 * 50) / 100);
                $j_pekerja  = ceil(($j_38 * 40) / 100);
                break;


            case 'ri_sc':
                $j_medis  = ceil(($j_38 * 55.38) / 100);
                $j_anastesi = ceil(($j_38 * 18) / 100);
                $j_penata  = ceil(($j_38 * 5.87) / 100);
                $j_resus  = ceil(($j_38 * 3.91) / 100);
                $j_pekerja  = ceil(($j_38 * 6.85) / 100);
                break;

            case 'ri_curet':
                $j_medis  = ceil(($j_38 * 59.7) / 100);
                $j_penata  = ceil(($j_38 * 5.9) / 100);
                $j_pekerja  = ceil(($j_38 * 24.4) / 100);
                break;

            case 'ri_hd':
                $jasa_hd = 884000;

                // per 1 februari 2024
                $pelayanan_hd = ceil(($jasa_hd * 11) / 100);
                $j_umum_s = ceil(($pelayanan_hd * 15) / 100);
                $j_sppdkgh = ceil(($pelayanan_hd * 15) / 100);
                // jasa dokter dpjp_hd
                $j_dpjp_hd = ceil(($pelayanan_hd * 35) / 100);

                if ($j_38 > 0) {
                    $sisa = $j_38 - ($j_umum_s + $j_sppdkgh + $j_dpjp_hd);

                    $j_rs = 0;
                    // sisa jasa dikurangi hd, dibagikan ke jasa lainnya
                    if ($sisa > 0) {
                        $j_rs = ceil(($sisa * 10) / 100);
                        $j_medis = ceil(($sisa * 36.21) / 100);
                        $j_pekerja = ceil(($sisa * 53.79) / 100);

                        //note: jasa untuk dpjp_hd, umum sertifikat, sppdkgh, tetap dibagikan.
                    }
                }
                break;

            default:
                # code...
                break;
        }
        $data = [
            'total_billing' => $total_billing,
            'chosaring' => $chosaring,
            'jasa_p' => $jasa,
            'klaim_min_rincian' => $klaim_rincian,
            'jasa_pelayanan' => $j_38,
            'jasa_rs' => $j_rs,
            'jasa_medis' => $j_medis,
            'jasa_operator' => $j_medis,
            'jasa_anastesi' => $j_anastesi,
            'jasa_penata' => $j_penata,
            'jasa_resus' => $j_resus,
            'jasa_pekerja' => $j_pekerja,
            'jasa_sppdkgh' => $j_sppdkgh,
            'jasa_um_sertifikat' => $j_umum_s,
            'jasa_dpjp_hd' => $j_dpjp_hd
        ];

        $prosentase = JmProsentase::updateOrCreate(
            ['jm_pasien_id' => $pasien->id],
            $data
        );

        return $prosentase;
    }

    // kalkulasi jasa per dokter sesuai status
    private function calcJasaDokterRanap($prosentase, $dokter, $kelompok)
    {
        // [01] delete jasa per dokter
        JmJasa::where('jm_prosentase_id', $prosentase->id)->delete();

        // [02] Get total visit spesialis & umum
        if ($kelompok === 'ri_no' || $kelompok === 'ri_hd') {
            // [02.01] kelompok Non Operatif & HD, get total visite spesialis seluruhnya,
            $totalSp = $dokter->where('status', 'sp')->sum('jumlah');
        } else {
            // [02.02] Selain kelompok diatas, remove data visite spesialis (sp) yang memiliki nama sama dengan dpjp
            $dokterFiltered = $dokter->reject(function ($dok) use ($dokter) {
                return $dok->status === 'sp' && $dokter->contains(function ($d) use ($dok) {
                    return $d->dokter === $dok->dokter && $d->status === 'dpjp';
                });
            });

            $totalSp = $dokterFiltered
                ->where('status', 'sp')
                ->sum('jumlah');
        }
        // [02.03] total visit dokter umum
        $totalUm = $dokter->where('status', 'um')->sum('jumlah');

        // [03] default jasa per dokter
        $status = null;
        $jasaSp = 0;
        $jasaUm = 0;
        $jasaAn = 0;
        $jasaDpjp = 0;
        $jasaSppdkgh = 0;
        $jasaUmSertifikat = 0;
        $jasaDpjpHd = 0;

        //[04] Hitung per kelompok jasa
        switch ($kelompok) {
            case 'ri_no':
                $status = 'RANAP NON OPERATIF';

                $jasa_per_dokter = 0;
                if ($prosentase->jasa_medis > 0) {
                    $jasa_per_dokter = $prosentase->jasa_medis / ((($totalSp * 2) + $totalUm) ?: 1);
                }
                $jasaSp = $jasa_per_dokter * 2;
                $jasaUm = $jasa_per_dokter;

                // remove dokter status == dpjp {Karna pembagian jasa by Visit}
                $dokter = $dokter->reject(function ($dok) {
                    return $dok->status === 'dpjp';
                });
                break;

            case 'ri_op':
                $status = 'RANAP OPERATIF';

                $totalJasaVisit = ($totalSp * 60000) + ($totalUm * 30000);
                $visitPercentage = ($totalJasaVisit * 100) / ($prosentase->jasa_medis ?: 1);
                //  Jika $jasa_medis > 0  dan total jasa visit tidak lebih dari 100% jasa dokter
                if ($prosentase->jasa_medis > 0) {
                    if ($visitPercentage  < 100) {
                        $jasaSp = 60000;
                        $jasaUm = 30000;
                        $jasaAn = $prosentase->jasa_anastesi - ceil(($totalJasaVisit * 50) / 100);
                        $jasaDpjp = $prosentase->jasa_medis - ceil(($totalJasaVisit * 50) / 100);
                    } else {
                        $jasaSp = 0;
                        $jasaUm = 0;
                        $jasaAn = $prosentase->jasa_anastesi;
                        $jasaDpjp = $prosentase->jasa_medis;
                    }
                }
                break;

            case 'ri_mata':
                $status = 'OPERATIF MATA';

                $totalJasaVisit = ($totalSp * 60000) + ($totalUm * 15000);
                $visitPercentage = ($totalJasaVisit * 100) / ($prosentase->jasa_medis ?: 1);

                if ($prosentase->jasa_medis > 0) {
                    if ($visitPercentage  < 100) {
                        $jasaSp = 60000;
                        $jasaUm = 15000;
                        $jasaAn = $prosentase->jasa_anastesi - ceil(($totalJasaVisit * 50) / 100);
                        $jasaDpjp = $prosentase->jasa_medis - ceil(($totalJasaVisit * 50) / 100);
                    } else {
                        $jasaSp = 0;
                        $jasaUm = 0;
                        $jasaAn = $prosentase->jasa_anastesi;
                        $jasaDpjp = $prosentase->jasa_medis;
                    }
                }
                break;

            case 'ri_partus':
                $status = 'PARTUS';
                $totalJasaVisit = ($totalSp * 60000) + ($totalUm * 15000);
                $visitPercentage = ($totalJasaVisit * 100) / ($prosentase->jasa_medis ?: 1);

                if ($prosentase->jasa_medis > 0) {
                    if ($visitPercentage < 100) {
                        $jasaSp = 60000;
                        $jasaUm = 15000;
                        $jasaDpjp = $prosentase->jasa_medis - $totalJasaVisit;
                    } else {
                        $jasaSp = 0;
                        $jasaUm = 0;
                        $jasaDpjp = $prosentase->jasa_medis;
                    }
                }
                break;

            case 'ri_sc':
                $status = 'SC';
                $totalJasaVisit = ($totalSp * 60000) + ($totalUm * 15000);
                $visitPercentage = ($totalJasaVisit * 100) / ($prosentase->jasa_medis ?: 1);

                if ($prosentase->jasa_medis > 0) {
                    if ($visitPercentage < 100) {
                        $jasaSp = 60000;
                        $jasaUm = 15000;
                        $jasaAn = $prosentase->jasa_anastesi - ceil(($totalJasaVisit * 50) / 100);
                        $jasaDpjp = $prosentase->jasa_medis - ceil(($totalJasaVisit * 50) / 100);
                    } else {
                        $jasaSp = 0;
                        $jasaUm = 0;
                        $jasaAn = $prosentase->jasa_anastesi;
                        $jasaDpjp = $prosentase->jasa_medis;
                    }
                }
                break;

            case 'ri_curet':
                $status = 'CURET';
                $totalJasaVisit = ($totalSp * 60000) + ($totalUm * 15000);
                $visitPercentage = ($totalJasaVisit * 100) / ($prosentase->jasa_medis ?: 1);

                if ($prosentase->jasa_medis > 0) {
                    if ($visitPercentage < 100) {
                        $jasaSp = 60000;
                        $jasaUm = 15000;
                        $jasaDpjp = $prosentase->jasa_medis - $totalJasaVisit;
                    } else {
                        $jasaSp = 0;
                        $jasaUm = 0;
                        $jasaDpjp = $prosentase->jasa_medis;
                    }
                }

                break;

            case 'ri_hd':
                $status = 'RANAP HD';

                $jasa_per_dokter = 0;
                if ($prosentase->jasa_medis > 0) {
                    $jasa_per_dokter = $prosentase->jasa_medis / (($totalSp * 2 + $totalUm) ?: 1);
                }
                if ($prosentase->jasa_medis > 0) {
                    $jasaSp = $jasa_per_dokter * 2;
                    $jasaUm = $jasa_per_dokter;
                    $jasaUmSertifikat = $prosentase->jasa_um_sertifikat;
                    $jasaSppdkgh = $prosentase->jasa_sppdkgh;
                    $jasaDpjpHd = $prosentase->jasa_dpjp_hd;
                }

                // remove dokter status == dpjp {Karna pembagian jasa by Visit}
                $dokter = $dokter->reject(function ($dok) {
                    return $dok->status === 'dpjp';
                });
                break;


            default:
                # code...
                break;
        }

        // [05] each data visit dokter
        foreach ($dokter as $key => $dok) {
            $jasaDokter = 0;
            $visit = $dok->jumlah;
            $SpIsDpjp = false;


            // [05.01] decide masing-masing dokter mendpatkan jasa yang mana.
            if ($dok->status === 'sp') {
                // hapus nama dokter yg memiliki nama sama dengan dpjp
                foreach ($dokter as $entry) {
                    if ($entry->status === 'dpjp' &&  $entry->dokter === $dok->dokter) {
                        $SpIsDpjp = true;
                        unset($dokter[$key]);
                        break;
                    }
                }

                if (!$SpIsDpjp) {
                    $jasaDokter = $jasaSp * $visit;
                }
            } else if ($dok->status === 'um') {
                $jasaDokter = $jasaUm * $visit;
            } else if ($dok->status === 'an') {
                $jasaDokter = $jasaAn;
            } else if ($dok->status === 'dpjp') {
                $jasaDokter = $jasaDpjp;
            } else if ($dok->status === 'um_s') {
                $jasaDokter = $jasaUmSertifikat;
            } else if ($dok->status === 'sppdkgh') {
                $jasaDokter = $jasaSppdkgh;
            } else if ($dok->status === 'dpjp_hd') {
                $jasaDokter = $jasaDpjpHd;
            }

            // [06] insert data jasa dokter
            if (!$SpIsDpjp) {
                $data = [
                    'jm_prosentase_id' => $prosentase->id,
                    'dokter' => $dok->dokter,
                    'status' => $status,
                    'jasa' => $jasaDokter,
                ];
                JmJasa::create($data);
            }
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
                cabar: 'jkmd',
                kelompok: $this->pilih_download_ranap,
                batch: $this->batch_ri
            ),
            'Rekap Jasa Ranap JKMD ' . $this->bulan_ri . '.xlsx'
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
        return view('livewire.jasmed.jkmd.ranap');
    }
}
