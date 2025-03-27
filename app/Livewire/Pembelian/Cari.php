<?php

namespace App\Livewire\Pembelian;

use Livewire\Component;
use App\Models\Gudang\Pembelian;
use Livewire\Attributes\Lazy;

#[Lazy]
class Cari extends Component
{
    public ?Pembelian $pembelian;

    function mount(?Pembelian $pembelian)
    {
        $this->pembelian = $pembelian;
    }

    // function placeholder()
    // {
    //     $skeleton  = file_get_contents(resource_path('views/components/skeleton.blade.php'));

    //     return <<<HTML
    //         <div class="relative bg-white rounded-md shadow-xl p-4 mt-1 border border-indigo-500 transition-transform transform">

    //             <div class="absolute top-[-12px] left-[3%] transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-b-8 border-transparent border-b-indigo-500"></div>

    //             <div class="flex flex-col gap-3">$skeleton</div>
    //         </div>
    //     HTML;
    // }

    public function render()
    {
        return view('livewire.pembelian.cari');
    }
}
