<?php

namespace App\Livewire\Akreditasi\Element;

use App\Models\Akreditasi\AkreBabElement;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use TallStackUi\Traits\Interactions;

#[Lazy]
class AddBab extends Component
{
    use Interactions;

    public ?int $chapter_id;
    public ?string $jenisPenomoran = 'alfabet';
    public ?int $parent;
    public ?string $nama, $deskripsi, $maksud_tujuan;
    public bool $bab;

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'deskripsi' => 'required',
            'maksud_tujuan' => 'required',
        ];
    }


    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $data = [
                'no' => $this->generateNomorOtomatis(),
                'chapter_id' => $this->chapter_id,
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'maksud_tujuan' => $this->maksud_tujuan,
                'bab' => $this->bab ? 'bab' : 'sub',
                'parent_id' => $this->parent,
            ];

            AkreBabElement::create($data);

            DB::commit();

            $this->toast()
                ->success('Berhasil', 'Bab baru ditambahkan.')
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
        $recordTerakhir = AkreBabElement::where('chapter_id', $this->chapter_id)
            ->latest('id')
            ->first();

        if (!$recordTerakhir) {
            // Jika belum ada data
            return $this->jenisPenomoran === 'alfabet' ? 'A' : '1';
            return;
        }

        // Generate nomor berikutnya
        if ($this->jenisPenomoran === 'alfabet') {
            return $this->getNextAlfabet($recordTerakhir->no);
        } else {
            return (int)$recordTerakhir->no + 1;
        }
    }

    private function getNextAlfabet($currentAlfabet)
    {
        // A -> B, B -> C, dst
        if (strlen($currentAlfabet) === 1) {
            $ascii = ord($currentAlfabet);
            if ($ascii < 90) { // A-Z
                return chr($ascii + 1);
            }
            return 'AA'; // Setelah Z
        }

        // AA -> AB, AB -> AC, dst
        return ++$currentAlfabet;
    }

    #[Computed]
    public function bab(): array
    {
        return AkreBabElement::where('bab', 'bab')
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

    public function mount($chapterId)
    {
        $this->chapter_id = $chapterId;
    }

    public function render()
    {
        return view('livewire.akreditasi.element.add-bab');
    }
}
