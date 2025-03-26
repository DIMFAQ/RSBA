<?php

namespace App\Livewire\Surat\Sp3;

use Livewire\Component;
use App\Models\Sdm\Jabatan;
use Livewire\Attributes\Lazy;
use App\Models\Surat\SuratSp3;
use App\Models\Master\Supplier;
use Illuminate\Support\Facades\DB;
use App\Models\Sdm\KaryawanJabatan;
use App\Models\Surat\SuratSp3Detail;
use TallStackUi\Traits\Interactions;

#[Lazy]
class Add extends Component
{
    use Interactions;

    public array $caraBayarOptions = [
        ['label' => 'Tunai', 'value' => 'tunai'],
        ['label' => 'Transfer', 'value' => 'trf'],
        ['label' => 'Giro', 'value' => 'giro'],
    ];

    public $mengetahuiOptions;
    public $tgl;
    public string $rekanan, $keterangan = '', $method_bayar;
    public int $mengetahui, $jabatan, $rekananId;
    public $listSp3 = [];

    protected $rules = [
        'tgl' => 'required',
        'rekanan' => 'required',
        'method_bayar' => 'required',
        'keterangan' => 'required',
        'jabatan' => 'required',
        'listSp3' => 'required|array|min:1'
    ];

    public function messages()
    {
        return [
            'listSp3.required' => 'Rincikan item pembayarannya.',
            'listSp3.min' => 'Silahkan rincikan item pembayarannya.'
        ];
    }

    public function mount()
    {
        $this->tgl = date('Y-m-d');
        $this->mengetahuiOptions = Jabatan::where('bagian_id', 1)->get()
            ->map(function ($item) {
                return [
                    'label' => $item->nama,
                    'value' => $item->id
                ];
            });
    }

    public function updatedRekananId($value)
    {
        // get nama vendor;
        $supplier = Supplier::findOrFail($value);

        if ($supplier) {
            $this->rekanan = $supplier->nama;
        } else {
            $this->toast()
                ->error('Not Found', 'Data supplier tidak ditemukan.')
                ->send();
        }
    }

    function updatedJabatan($value)
    {
        // get id user karyawan base jabatan
        $karyawanJabatan = KaryawanJabatan::with('karyawan')
            ->where('jabatan_id', $value)
            ->orderBy('id', 'desc')
            ->first();

        if ($karyawanJabatan) {
            $this->mengetahui = $karyawanJabatan->karyawan->id;
        } else {
            $this->toast()
                ->error('Not Found', 'Mengetahui tidak ditemukan.')
                ->send();
        }
    }

    public function submit()
    {
        $this->validate();
        $data = [
            'no' => $this->createNomor(),
            'tahun' => date('Y', strtotime($this->tgl)),
            'tgl' => $this->tgl,
            'rekanan' => $this->rekanan,
            'bayar' => $this->method_bayar,
            'keterangan' => $this->keterangan,
            'disetujui' => $this->mengetahui,
            'jabatan' => $this->jabatan,
            'created_by' => auth()->user()->id,
        ];

        DB::beginTransaction();
        try {
            $suratSp3 = SuratSp3::create($data);

            // mapping detail sp3
            $itemsDetail = collect($this->listSp3)
                ->map(
                    function ($item) use ($suratSp3) {
                        return [
                            'sp3_id' => $suratSp3->id,
                            'keterangan' => $item['keterangan'],
                            'nominal' => $item['nominal'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                )->toArray();
            // insert into database
            SuratSp3Detail::insert($itemsDetail);
            DB::commit();
            $this->dispatch('created-sp3');

            $this->toast()
                ->success('Berhasil', 'SP3 berhasil disimpan.')
                ->send();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toast()
                ->error('Gagal Menyimpan Data', 'error : ' . $th->getMessage())
                ->send();
        }
    }

    private function createNomor()
    {
        $tahun = date('Y', strtotime($this->tgl));

        $last = SuratSp3::select('no')
            ->whereYear('tahun', $tahun)
            ->where('jabatan', $this->jabatan)
            ->orderBy('id', 'desc')
            ->first();

        $no = 1;
        if ($last) {
            $no = (int)$last->no + 1;
        }
        return $no;
    }

    public function render()
    {
        return view('livewire.surat.sp3.add');
    }
}
