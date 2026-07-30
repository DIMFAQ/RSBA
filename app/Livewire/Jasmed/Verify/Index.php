<?php

namespace App\Livewire\Jasmed\Verify;

use Carbon\Carbon;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Index extends Component
{

    public $tab;

    public $periode;
    public $layanan;
    public array $layanan_opt = [
        ['label' => 'Rajal', 'value' => 'rajal'],
        ['label' => 'Ranap', 'value' => 'ranap'],
    ];

    public $cabar = 'bpjs';
    public array $cabar_opt = [
        ['label' => 'BPJS', 'value' => 'bpjs'],
        ['label' => 'Tunai', 'value' => 'tunai'],
        ['label' => 'JKMD', 'value' => 'jkmd']
    ];

    public $kelompok;
    public array $kelompok_opt = [];


    public $batch;
    public array $batchOptions = [
        ['label' => '1', 'value' => '1'],
        ['label' => '2', 'value' => '2'],
        ['label' => '3', 'value' => '3'],
    ];

    public function updatedLayanan($value)
    {
        $kelompok_ri =  [
            ['label' => 'Non Operatif', 'value' => 'ri_no'],
            ['label' => 'Operatif', 'value' => 'ri_op'],
            ['label' => 'Mata', 'value' => 'ri_mata'],
            ['label' => 'Partus', 'value' => 'ri_partus'],
            ['label' => 'SC', 'value' => 'ri_sc'],
            ['label' => 'Curet', 'value' => 'ri_curet'],
            ['label' => 'HD', 'value' => 'ri_hd'],
        ];

        $kelompok_rj = [
            ['label' => 'Spesialis', 'value' => 'rj_sp'],
            ['label' => 'Umum', 'value' => 'rj_um'],
            ['label' => 'Mata', 'value' => 'rj_mata'],
            ['label' => 'HD', 'value' => 'rj_hd'],
        ];

        $this->kelompok_opt = $value === 'rajal' ? $kelompok_rj : $kelompok_ri;
    }

    public function mount()
    {
        $this->periode = Carbon::now()->format('Y-m');
    }

    public function cari() {}


    public function render()
    {
        return view('livewire.jasmed.verify.index');
    }
}
