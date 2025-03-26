<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Surat\SuratCuti;
use Illuminate\Support\Facades\DB;

class SuratCutiForm extends Form
{
    public ?array $tgl_cuti = [];
    public ?string $jenis_cuti;
    public int $sisa_cuti = 0, $lama_cuti = 0;
    public ?array $atasan = [];
    public ?string $keterangan = null;
    public ?string $alamat = null;
    public $options_urgensi = [
        ['id' => 'tahunan', 'label' => 'Cuti Tahunan'],
        ['id' => 'besar', 'label' => 'Cuti Besar'],
        ['id' => 'sakit', 'label' => 'Sakit'],
        ['id' => 'bersalin', 'label' => 'Bersalin'],
        ['id' => 'penting', 'label' => 'Kepentingan Lain'],
        ['id' => 'lain', 'label' => 'Lain-Lain'],
    ];
    public $options_atasan, $karyawan_options;

    //
    // validations
    public function rules()
    {
        return [
            'jenis_cuti' => 'required',
            'tgl_cuti' => 'required|array|min:1',
            'tgl_cuti.*' => 'date',
            'lama_cuti' => 'required|integer|min:1|max:' . $this->sisa_cuti,
            'atasan' => 'required|array|min:1',
        ];
    }

    public function messages()
    {
        return [
            'lama_cuti.max' => 'Lama cuti tidak dapat lebih dari sisa cuti.',
        ];
    }

    // generate no surat 
    public static function generateNoSurat(): string
    {
        $tahun = date('Y');
        $last = SuratCuti::select('id', 'no_surat')
            ->whereYear('tgl_surat', $tahun)
            ->orderBy('id', 'desc')
            ->first();

        $no = 1;
        if ($last) {
            $no = (int)substr($last->no_surat, 1, 4) + 1;
        }
        // buat nomor jadi 3 digit
        $no = str_pad($no, 4, '0', STR_PAD_LEFT);

        return "C{$no}{$tahun}";
    }


    public function submiting($karyawan)
    {
        $sisaAkhirCuti = $this->sisa_cuti - $this->lama_cuti;
        sort($this->tgl_cuti); // sort array $tgl_cuti
        $tgl_mulai = $this->tgl_cuti[0]; // First date
        $tgl_akhir = $this->tgl_cuti[count($this->tgl_cuti) - 1]; // Last date

        // populate data
        $dataSuratCuti = [
            'karyawan_id' => $karyawan?->id,
            'no_surat' => $this->generateNoSurat(),
            'tgl_surat' => date('Y-m-d'),
            'tgl_mulai' => $tgl_mulai,
            'tgl_akhir' => $tgl_akhir,
            'tgl_cuti' => json_encode($this->tgl_cuti),
            'lama_cuti' => $this->lama_cuti,
            'urgensi' => $this->jenis_cuti,
            'keterangan' => $this->keterangan,
            'alamat' => $this->alamat,
            'acc' => json_encode($this->atasan),
            'created_by' => auth()->user()->id
        ];

        DB::beginTransaction();
        try {
            SuratCuti::create($dataSuratCuti); //create record 

            $karyawan->cuti = $sisaAkhirCuti; //update sisa cuti
            $karyawan->save();
            DB::commit();

            return [
                'status' => 'sukses',
                'message' => 'Inserted'
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
