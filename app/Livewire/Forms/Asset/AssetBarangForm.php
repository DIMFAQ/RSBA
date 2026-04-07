<?php

namespace App\Livewire\Forms\Asset;

use Throwable;
use Exception;
use App\Models\Assets\AssetBarang;
use Livewire\Form;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;

class AssetBarangForm extends Form
{
    public ?int $distribusi_det_id;
    public ?int $barang_id;
    public ?int $ruangan_id;
    public ?float $nilai;
    public ?string $tanggal_catat;


    public ?int $main = null;
    public ?string $tgl_catat;
    public ?string $status, $keterangan = null;

    public function rules(): array
    {
        return [
            'distribusi_det_id' => 'required',
            'barang_id' => 'required',
            'ruangan_id' => 'required',
            'nilai' => 'required',
            'tanggal_catat' => 'required',
            'tgl_catat' => 'required',
            'status' => 'required'
        ];
    }


    // menambah asset barang saat distribusi
    public function adding($distribusi_det_id, $barang_id, $ruangan_id, $nilai, $tanggal_catat): void
    {
        DB::transaction(function () use ($distribusi_det_id, $barang_id, $ruangan_id, $nilai, $tanggal_catat) {
            AssetBarang::create([
                'distribusi_det_id' => $distribusi_det_id,
                'barang_id' => $barang_id,
                'ruangan_id' => $ruangan_id,
                'nilai' => $nilai,
                'tanggal_catat' => $tanggal_catat
            ]);
        });
    }


    public function catatAsset(AssetBarang $assetBarang): void
    {
        DB::beginTransaction();
        try {
            // Set Data
            $data = [
                'kode' => $this->generateKodeAsset($assetBarang),
                'tanggal_catat' => $this->tgl_catat,
                'status' => $this->status,
                'main_asset_id' => $this->main ?? null,
                'jenis' => $this->main ? 'component' : 'main',
                'level' => $this->main ? 1 : 0,
                'keterangan' => $this->keterangan,
            ];

            // Update Asset
            $assetBarang->update($data);

            // Create Logs Asset
            $assetBarang->logs()->create([
                'asset_id' => $assetBarang->id,
                'status' => 'info',
                'keterangan' => 'Mulai dicatat sebagai asset Baru, dengan nomor: ' . $assetBarang->kode . ', dan kondisi: ' . $this->status,
                'user_id' => auth()->id(),
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }


    // FIX THIS: generate kode asset untuk sub selalu ambil kode asset terakhir,
    protected function generateKodeAsset(AssetBarang $assetBarang): string
    {
        // Generate Kode 
        /**
         * @return string
         * Nomor Urut Berdasarkan barang->kategori->prefix dan tahun pencataatan
         * Contoh Kode Asset: 'COM/1024/0001.001'
         * COM : Computer, kategori barang komputer
         * 1024 : 10 = Bulan Oktober, 24 = Tahun 2024
         * 0001 : Nomor urut asset -> jika sub maka nomor sama dengan asset utama, dan ditambahkan .001
         * 001 : Sub asset dari asset 0001s
         */

        $prefix = $assetBarang->barang->kategori->prefix;
        [$tahun, $bulan] = explode('-', date('Y-m', strtotime($this->tgl_catat)));

        // Last Kode Asset
        $lastQuery = AssetBarang::select('id', 'kode')
            ->whereYear('tanggal_catat', $tahun)
            ->where([
                ['kode', '!=', null],
                ['kode', '!=', '']
            ])
            ->whereHas('barang', function ($query) use ($prefix) {
                // Use prefix through the relation:
                $query->whereHas('kategori', function ($q) use ($prefix) {
                    $q->where('prefix', $prefix);
                });
            });

        if (empty($this->main)) {
            $lastQuery->where('jenis', 'main');
        } else {
            $lastQuery->where('main_asset_id', $this->main);
        }

        // Last Kode
        $last = $lastQuery->orderBy('id', 'desc')->first();

        $mainNo = 1;
        $subNo = 1;
        if ($last) {
            [$_, $_, $lastNo] = explode('/', $last->kode);

            // bukan asset sub
            $mainNo = (int)$lastNo + 1;

            // jika ini adalah sub asset ,atau part dari asset utamas
            if ($this->main) {
                $lastSub = explode('.', $lastNo);
                if (count($lastSub) > 1) {
                    $lastSubNo = $lastSub[1];
                    $subNo = (int)$lastSubNo + 1;
                }

                // Jika sub asset, maka nomor utama tetap sama
                $mainNo = (int)$lastSub[0];
            }
        }

        // buat nomor jadi 4 digit
        $mainNo = str_pad($mainNo, 4, '0', STR_PAD_LEFT);
        // buat nomor sub asset jadi 3 digit
        $subNo = str_pad($subNo, 3, '0', STR_PAD_LEFT);

        $nomor =  $this->main ? $mainNo . '.' . $subNo : $mainNo;
        // return string formated kode
        return $prefix . '/' . $bulan . date('y', strtotime($tahun)) . '/' . $nomor;
    }
}
