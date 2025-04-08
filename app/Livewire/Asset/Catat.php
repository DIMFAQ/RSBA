<?php

namespace App\Livewire\Asset;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use App\Models\Assets\AssetBarang;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;

#[Lazy]
class Catat extends Component
{
    use Interactions;

    public ?AssetBarang $assetBarang;

    public $main;
    public $tgl_catat;
    public $status;
    public $keterangan;

    public $statusOptions = [
        'baik' => 'Baik',
        'diperbaiki' => 'Perbaikan',
        'rusak' => 'Rusak',
        'hulan' => 'Hilang',
    ];

    public $rules = [
        // 'main' => 'nullable|exists:asset_barang,id',
        'tgl_catat' => 'required',
        'status' => 'required',
    ];

    public function mount($id)
    {
        $this->assetBarang = AssetBarang::findOrFail($id);
    }

    function submit()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $data = [
                'kode' => $this->generateKodeAsset(),
                'tanggal_catat' => $this->tgl_catat,
                'status' => $this->status,
                'main_asset_id' => $this->main,
            ];

            $this->assetBarang->update($data);

            // create logs asset
            $this->assetBarang->logs()->create([
                'asset_id' => $this->assetBarang->id,
                'status' => 'info',
                'keterangan' => 'Mulai dicatat sebagai asset Baru, dengan nomor: ' . $this->assetBarang->kode . ', dan kondisi: ' . $this->status,
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            // event
            $this->dispatch('new-asset-created');

            $this->toast()
                ->success('Berhasil', 'Data berhasil disimpan')
                ->send();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toast()
                ->error('Gagal', 'Data gagal disimpan, Error : ' . $th->getMessage() . 'line :' . $th->getLine())
                ->send();
        }
    }

    private function generateKodeAsset(): string
    {
        // Generate Kode 
        /**
         * Contoh Kode Asset: 'COM/1024/0001.001'
         * COM : Computer, kategori barang komputer
         * 1024 : 10 = Bulan Oktober, 24 = Tahun 2024
         * 0001 : Nomor urut asset
         * 001 : Sub asset dari asset 0001
         */

        $prefix = $this->assetBarang->barang->kategori->prefix;
        [$tahun, $bulan] = explode('-', date('Y-m', strtotime($this->tgl_catat)));

        // get last asset
        $last = AssetBarang::select('id', 'kode')
            ->whereYear('tanggal_catat', $tahun)
            ->whereNot('kode', null)
            ->orWhereNot('kode', '')
            ->orderBy('id', 'desc')
            ->first();

        $mainNo = 1;
        $subNo = 1;
        if ($last) {
            [$_, $_, $lastNo] = explode('/', $last->kode);

            // jika ini adalah sub asset ,atau part dari asset utamas
            if ($this->main) {
                $lastSub = explode('.', $lastNo);
                if (count($lastSub) > 1) {
                    $lastSubNo = $lastSub[1];
                    $subNo = (int)$lastSubNo + 1;
                }
            }
            // bukan asset sub
            $mainNo = (int)$lastNo + 1;
        }

        // buat nomor jadi 4 digit
        $mainNo = str_pad($mainNo, 4, '0', STR_PAD_LEFT);
        // buat nomor sub asset jadi 3 digit
        $subNo = str_pad($subNo, 3, '0', STR_PAD_LEFT);

        $nomor =  $this->main ? $mainNo . '.' . $subNo : $mainNo;


        // return string formated kode
        return $prefix . '/' . $bulan . date('y', strtotime($tahun)) . '/' . $nomor;
    }

    public function render()
    {

        // $mains = AssetBarang::all();
        return view('livewire.asset.catat');
    }
}
