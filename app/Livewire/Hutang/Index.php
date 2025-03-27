<?php

namespace App\Livewire\Hutang;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use App\Models\Gudang\Pembelian;

#[Title('Hutang')]
#[Lazy]
class Index extends Component
{
    public $periode, $due_date;
    public $jenis, $vendor;

    // STATS
    public float $hutang, $dibayar, $belumDibayar;

    public $optionsFaktur = [
        ['label' => 'Alat Kesehatan', 'value' => 'alkes'],
        ['label' => 'BHP', 'value' => 'bhp'],
        ['label' => 'Umum', 'value' => 'umum'],
    ];

    public function mount()
    {
        $this->periode = date('Y-m-d');
    }

    function getData()
    {
        return Pembelian::with('supplier')
            ->when($this->periode, function ($query) {
                $query->whereMonth('tgl', date('m', strtotime($this->periode)))
                    ->whereYear('tgl', date('Y', strtotime($this->periode)));
            })
            ->when($this->vendor, function ($query) {
                $query->where('supplier_id', $this->vendor);
            });
    }


    public function stats()
    {
        $pembelian = $this->getData();
        $this->hutang = $pembelian->sum('total');
        // sudah dibayar
        $this->dibayar = (clone $pembelian)->where('status_pembayaran', 'lunas')->sum('total');
        // belum dibayar
        // $this->belumDibayar = $this->hutang - $this->dibayar;
        $this->belumDibayar = (clone $pembelian)->where('status_pembayaran', null)->orWhere('status_pembayaran', 'tempo')->sum('total');
    }

    function updatedPeriode()
    {
        $this->stats();
    }

    function updatedVendor()
    {
        $this->stats();
    }

    public function render()
    {
        return view('livewire.hutang.index');
    }
}
