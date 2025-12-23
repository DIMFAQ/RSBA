<?php

namespace App\Livewire\Pembelian\Permintaan;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Lazy]
#[Title('Pengajuan Pengadaan')]
class Index extends Component
{
    public function render()
    {
        $this->authorize('view-pengajuan');
        return view('livewire.pembelian.permintaan.index');
    }
}
