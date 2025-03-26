<?php

namespace App\Exports;

use App\Exports\RanapProsentase;
use App\Exports\RekapJasaDokter;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RanapExport implements WithMultipleSheets
{
    protected $periode;
    protected $kelompok;
    protected $pelayanan = 'ranap';
    protected $batch;

    public function __construct($periode, $kelompok, $batch)
    {
        $this->periode = $periode;
        $this->kelompok = $kelompok;
        $this->batch = $batch;
    }

    public function sheets(): array
    {
        // selected kelompok nya apa 
        if ($this->kelompok) {
            return [
                'Sheet1' => new RanapProsentase($this->periode, $this->kelompok, $this->batch),
                'Sheet2' => new RekapJasaDokter($this->periode, $this->kelompok, $this->pelayanan, $this->batch)
            ];
        }
        // tidak diselected kelompoknya
        else {
            $semua_kelompok = [
                'ri_no',
                'ri_op',
                'ri_mata',
                'ri_partus',
                'ri_sc',
                'ri_curet',
                'ri_hd'
            ];

            $sheets = [];

            foreach ($semua_kelompok as $kelompok) {
                $sheets[] = new RanapProsentase($this->periode, $kelompok, $this->batch);
            }

            $sheets[] = new RekapJasaDokter($this->periode, $this->kelompok, $this->pelayanan, $this->batch);

            return $sheets;
        }
    }
}
