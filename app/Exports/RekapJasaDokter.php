<?php

namespace App\Exports;

use App\Models\JmJasa;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RekapJasaDokter implements FromCollection, WithHeadings, WithTitle
{

    private $periode;
    private $kelompok;
    private $pelayanan;
    private $batch;

    public function __construct($periode, $kelompok, $pelayanan, $batch)
    {
        $this->periode = $periode;
        $this->kelompok  = $kelompok;
        $this->pelayanan = $pelayanan;
        $this->batch = $batch;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        [$tahun, $bulan] = explode('-', $this->periode);

        $query = JmJasa::select(
            'no_rekmedis',
            'nama_pasien',
            'tgl_checkout',
            'status',
            DB::raw('ROUND(jasa) as jasa'),
            'dokter'
        )
            ->leftJoin('jm_prosentase', 'jm_jasa.jm_prosentase_id', '=', 'jm_prosentase.id')
            ->leftJoin('jm_pasien', 'jm_prosentase.jm_pasien_id', '=', 'jm_pasien.id')
            ->whereYear('jm_pasien.tgl_checkout', $tahun)
            ->whereMonth('jm_pasien.tgl_checkout', $bulan)
            ->where('jm_pasien.layanan', $this->pelayanan)
            ->where('jm_pasien.batch', $this->batch)
            ->where('jm_pasien.cabar', 'bpjs');

        if ($this->kelompok) {
            $query->where('jm_pasien.kelompok', $this->kelompok);
        }

        // return 
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No. Rekmedis',
            'Nama Pasien',
            'Tanggal',
            'Status',
            'Jasa',
            'Dokter'
        ];
    }

    public function title(): string
    {
        return 'Jasa Dokter';
    }
}
