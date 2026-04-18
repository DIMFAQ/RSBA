<?php

namespace App\Livewire\Laporan\Umum;

use App\Traits\AuthorizesFromRoute;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Laporan Umum')]
#[Lazy]
class Index extends Component
{
    use AuthorizesFromRoute;

    public string $tab;

    public $optionsFaktur = [
        ['label' => 'Alat Kesehatan', 'value' => 'alkes'],
        ['label' => 'BHP', 'value' => 'bhp'],
        ['label' => 'Umum', 'value' => 'umum'],
    ];
    public array $periode = [];
    public ?array $items = [];
    public $jenis, $vendor, $ruangan;

    public function mount()
    {
        $this->tab = 'Pembelian';
        $this->periode = [
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString(),
        ];

        // dd($this->periode);
    }

    public function cariPembelian(): void
    {
        $this->validateOnly('periode', ['periode' => 'required|array']);
        $this->dispatch(
            'cariLaporanPembelian',
            periode: $this->periode,
            items: $this->items,
            vendor: $this->vendor,
            jenis: $this->jenis,
        );
    }

    public function cariDistribusi(): void
    {
        $this->validateOnly('periode', ['periode' => 'required|array']);
        $this->dispatch(
            'cariLaporanDistribusi',
            periode: $this->periode,
            items: $this->items,
            ruangan: $this->ruangan,
        );
    }

    public function render()
    {
        $this->authorizeFromRoute();
        return view('livewire.laporan.umum.index');
    }
}
