<?php

namespace App\Livewire\Master\Jabatan;

use App\Models\Sdm\Bagian;
use App\Models\Sdm\Jabatan;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class HierarchyChart extends Component
{
    public $bagian_id = '';

    public function render()
    {
        $nodes = Jabatan::getOrgChartNodes($this->bagian_id ?: null);
        $bagianOptions = Bagian::select('id', 'nama')->get()->map(fn($b) => [
            'value' => $b->id,
            'label' => $b->nama,
        ])->toArray();

        return view('livewire.master.jabatan.hierarchy-chart', [
            'nodes' => $nodes,
            'bagianOptions' => $bagianOptions,
        ]);
    }
}
