<?php

namespace App\Livewire\Jasmed\Verify;

use App\Models\JmDokterJasa;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Jasa extends Component
{
    public $jmDokterJasa;

    public function mount($prosentaseId)
    {
        $this->jmDokterJasa = JmDokterJasa::where('jm_prosentase_id', $prosentaseId)->get();
    }


    #[Computed]
    public function headers(): array
    {
        return [
            ['index' => 'dokter', 'label' => 'Nama Dokter'],
            ['index' => 'status_label', 'label' => 'Status'],
            ['index' => 'jumlah_visit', 'label' => 'Jumlah Visit'],
            ['index' => 'status_jasa', 'label' => 'Kelompok Jasa'],
            ['index' => 'jasa', 'label' => 'Jasa', 'format' => 'float']
        ];
    }

    #[Computed]
    public function rows(): array
    {
        return $this->jmDokterJasa->map(function ($item) {
            return [
                'dokter' => $item->dokter,
                'status_label' => $item->status_label,
                'jumlah_visit' => $item->jumlah,
                'status_jasa' => $item->status_jasa,
                'jasa' => $item->jasa
            ];
        })->toArray();
    }



    public function render()
    {
        return view('livewire.jasmed.verify.jasa');
    }
}
