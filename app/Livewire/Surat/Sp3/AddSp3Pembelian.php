<?php

namespace App\Livewire\Surat\Sp3;

use Livewire\Component;
use App\Models\Sdm\Jabatan;
use Livewire\Attributes\Lazy;
use App\Models\Surat\SuratSp3;
use App\Models\Gudang\Pembelian;
use Illuminate\Support\Facades\DB;
use App\Models\Surat\SuratSp3Detail;
use TallStackUi\Traits\Interactions;

#[Lazy]
class AddSp3Pembelian extends Component
{
    public ?Pembelian $pembelian;

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
    public array $listSp3 = [];
    public $totalPembayaran = 0;

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

    public function mount($id)
    {
        $this->pembelian = Pembelian::with(['details'])->find($id);

        if ($this->pembelian) {
            $this->rekanan = $this->pembelian->supplier->nama;
            $this->keterangan = "Pembayaran ke {$this->rekanan} untuk pembelian nomor transaksi {$this->pembelian->no}";

            $this->listSp3 = $this->pembelian->details->map(function ($item) {
                $subtotal = $item->jumlah * $item->harga_satuan;
                return [
                    'nominal' => $subtotal,
                    'keterangan' => "{$item->barang->nama} @{$item->jumlah} x " . number_format($item->harga_satuan, 0, ',', '.'),
                ];
            })->toArray();

            $this->totalPembayaran = $this->pembelian->details
                ->map(function ($item) {
                    $subtotal = 0;
                    $subtotal += ($item->jumlah * $item->harga_satuan);

                    return $subtotal;
                });


            $this->tgl = date('Y-m-d');
            $this->mengetahuiOptions = Jabatan::where('bagian_id', 1)->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->nama,
                        'value' => $item->id
                    ];
                });
        }
    }

    function updatedJabatan($value)
    {
        // get id user karyawan base jabatan
        $jabatan = Jabatan::find($value);

        if ($jabatan) {
            $karyawanJabatan = $jabatan->jabatans()
                ->where('tgl_berakhir', null)
                ->orderBy('id', 'desc')
                ->first();

            if ($karyawanJabatan) {
                $this->mengetahui = $karyawanJabatan->karyawan->id;
            } else {
                $this->toast()
                    ->error('Pejabat tidak ditemukan.', "Tidak ada karyawan dengan jabatan <b>{$jabatan->nama}</b>.")
                    ->send();
            }
        } else {
            $this->toast()
                ->error('Harus Diisi', 'Mengetahui harus dipilih.')
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
            'jabatan_id' => $this->jabatan,
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
        // Format Nomor {no}/S4/SP.3/PBA-{kode_surat_jabatan (A10,A11,A12)}/{tanggal 14.04.2025}

        $last = SuratSp3::select('no')
            ->where('jabatan_id', $this->jabatan)
            ->orderBy('id', 'desc')
            ->first();

        $jabatan = Jabatan::find($this->jabatan);

        $tanggal = date('d.m.Y', strtotime($this->tgl));
        $no = 1;
        if ($last) {
            $fullNomor = explode('/', $last->no);
            $lastNomor = $fullNomor[0];
            $no = (int)$lastNomor + 1;
        }
        return "{$no}/S4/SP.3/PBA-{$jabatan->kode_surat}/{$tanggal}";
    }

    public function render()
    {
        return view('livewire.surat.sp3.add-sp3-pembelian');
    }
}
