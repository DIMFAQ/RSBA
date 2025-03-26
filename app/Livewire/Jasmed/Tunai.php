<?php

namespace App\Livewire\Jasmed;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Jasmed Tunai')]
class Tunai extends Component
{
    public $test;

    protected $rules = [
        'test' => 'required|int'
    ];

    function submit()
    {
        $this->validate();

        dd($this->test);
    }

    public function render()
    {
        return view('livewire.jasmed.tunai');
    }
}
