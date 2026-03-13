<?php

namespace App\Livewire\Karyawan;

use App\Traits\AuthorizesFromRoute;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

#[Title('Karyawan')]
#[Lazy]
class Index extends Component
{
    use AuthorizesFromRoute;
    use Interactions;

    public $content = 'all';

    public function navigateTo($route)
    {
        $this->content = $route;
    }

    public function  downloadKaryawan()
    {
        $this->toast()
            ->info('Woops!', 'Fitur ini belum tersedia')
            ->send();
    }

    public function render()
    {
        $this->authorizeFromRoute();
        return view('livewire.karyawan.index');
    }
}
