<?php

namespace App\Livewire\Akreditasi\Ep;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\Akreditasi\AkreBabElement;
use App\Models\Akreditasi\AkreElement;
use TallStackUi\Traits\Interactions;

#[Lazy]
class AddElementPenilaian extends Component
{
    use Interactions;

    public ?int $bab_id;
    public ?array $methode = [];
    public ?string $jenisPenomoran = 'alfabet';
    public ?string $element, $kelengkapan;
    public ?int $target_nilai = 10;


    #[Computed]
    public function bab(): array
    {
        return AkreBabElement::where('bab', 'sub')
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->nama,
                    'value' => $item->id
                ];
            })
            ->values()
            ->toArray();
    }

    #[Computed]
    public function methode(): array
    {
        return [
            ['value' => 'D', 'label' => 'Dokument'],
            ['value' => 'O', 'label' => 'Observasi'],
            ['value' => 'R', 'label' => 'Regulasi'],
            ['value' => 'S', 'label' => 'Simulasi'],
            ['value' => 'T', 'label' => 'Telusur'],
            ['value' => 'W', 'label' => 'Wawancara'],

        ];
    }


    public function rules(): array
    {
        return [
            'bab_id' => 'required',
            'element' => 'required',
            'methode' => 'required',
            'kelengkapan' => 'required',
            'target_nilai' => 'required',
        ];
    }


    public function submit()
    {
        $this->validate();

        DB::beginTransaction();
        try {

            $data = [
                'akre_bab_id' => $this->bab_id,
                'nomor' => $this->generateNomorOtomatis(),
                'element' => $this->element,
                'methode' => $this->methode,
                'kelengkapan' => $this->kelengkapan,
                'target_nilai' => $this->target_nilai,
            ];

            AkreElement::create($data);

            DB::commit();

            $this->toast()
                ->success('Berhasil', 'Barhasil menambah element nilai.')
                ->send();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->toast()
                ->error('Tidak Berhasil', $e->getMessage())
                ->send();
        }
    }


    public function generateNomorOtomatis()
    {
        // Ambil nomor terakhir berdasarkan jenis penomoran
        $recordTerakhir = AkreElement::where('akre_bab_id', $this->bab_id)
            ->latest('id')
            ->first();

        if (!$recordTerakhir) {
            // Jika belum ada data
            return $this->jenisPenomoran === 'alfabet' ? 'a' : '1';
            return;
        }

        // Generate nomor berikutnya
        if ($this->jenisPenomoran === 'alfabet') {
            return $this->getNextAlfabet($recordTerakhir->nomor);
        } else {
            return (int)$recordTerakhir->nomor + 1;
        }
    }

    private function getNextAlfabet($currentAlfabet)
    {
        // a -> b, b -> c, dst
        if (strlen($currentAlfabet) === 1) {
            $ascii = ord($currentAlfabet);
            if ($ascii < 122) { // a-z (122 adalah 'z')
                return chr($ascii + 1);
            }
            return 'aa'; // Setelah z
        }

        // aa -> ab, ab -> ac, dst
        // Untuk multi-karakter, increment seperti counter
        $chars = str_split($currentAlfabet);
        $carry = true;

        for ($i = count($chars) - 1; $i >= 0 && $carry; $i--) {
            if ($chars[$i] === 'z') {
                $chars[$i] = 'a';
            } else {
                $chars[$i] = chr(ord($chars[$i]) + 1);
                $carry = false;
            }
        }

        // Jika masih carry, tambah karakter baru di depan
        if ($carry) {
            array_unshift($chars, 'a');
        }

        return implode('', $chars);
    }

    public function render()
    {
        return view('livewire.akreditasi.ep.add-element-penilaian');
    }
}
