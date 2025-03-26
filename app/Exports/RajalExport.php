<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RajalExport implements WithMultipleSheets
{
    protected $periode;
    protected $kelompok;
    protected $pelayanan = 'rajal';
    protected $batch;

    public function __construct($periode, $kelompok, $batch)
    {
        $this->periode = $periode;
        $this->kelompok = $kelompok;
        $this->batch = $batch;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {

        if ($this->kelompok) {
            return [
                'Sheet1' => new RajalProsentase($this->periode, $this->kelompok, $this->batch),
                'Sheet2' => new RekapJasaDokter($this->periode, $this->kelompok, $this->pelayanan, $this->batch),
            ];
        }
        // kelompok tidak dipilih
        else {
            $semua_kelompok = [
                'rj_sp',
                'rj_um',
                'rj_mata',
                'rj_hd'
            ];

            $sheets = [];

            foreach ($semua_kelompok as $kelompok) {
                // prosentase per kelompok
                $sheets[] = new RajalProsentase($this->periode, $kelompok, $this->batch);
            }

            // sheets rekap dokter
            $sheets[] = new RekapJasaDokter($this->periode, $this->kelompok, $this->pelayanan, $this->batch);

            return $sheets;
        }
    }
}
