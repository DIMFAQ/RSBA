<?php

namespace App\Livewire\Jasmed;

use App\Models\JmJasa;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class DetailsJasaDokter extends Component
{
    public $periode, $layanan, $cabar, $batch;

    public function mount($periode, $layanan, $cabar, $batch)
    {
        $this->periode = $periode;
        $this->layanan = $layanan;
        $this->cabar = $cabar;
        $this->batch = $batch;
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['index' => 'dokter', 'label' => 'Nama Dokter'],
            ['index' => 'total_jasa', 'label' => 'Total Jasa', 'format' => 'float'],
        ];
    }

    #[Computed]
    public function getJasaSum()
    {
        $periode = $this->periode;
        $layanan = $this->layanan;
        $cabar = $this->cabar;
        $batch  = $this->batch;

        $data =  JmJasa::whereHas(
            'prosentase.pasien',
            function ($query) use ($periode, $layanan, $cabar, $batch) {
                $query //Query to relations JmPasien (as above)
                    ->where('tgl_checkout', 'like', "$periode%")
                    ->when($layanan, fn($query) => $query->where('layanan', $layanan))
                    ->when($cabar, fn($query) => $query->where('cabar', $cabar))
                    ->when($batch, fn($query) => $query->where('batch', $batch))
                ;
            }
        )
            ->selectRaw('dokter, SUM(jasa) as total_jasa')
            ->groupBy('dokter');

        return $data->get();
    }

    public function rows(): array
    {
        return $this->getJasaSum()->map(function ($item) {
            return [
                'dokter' => $item->dokter,
                'total_jasa' => $item->total_jasa
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.jasmed.details-jasa-dokter');
    }
}
