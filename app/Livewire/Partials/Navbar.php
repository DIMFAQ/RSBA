<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Auth\Login;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Isolate;
use Illuminate\Support\Facades\Auth;
use TallStackUi\Traits\Interactions;

#[Lazy]
#[Isolate]
class Navbar extends Component
{
    use Interactions;

    public $title;

    public function mount($title)
    {
        $this->title = $title;
    }

    #[On('update-title')]
    function updateTitle($title)
    {
        $this->title = $title;
    }

    function logout()
    {
        try {
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();

            return $this->redirect(Login::class, navigate: true);
        } catch (\Throwable $e) {
            $this->toast()
                ->error("An error occured {$e}")
                ->send();
        }
    }

    public function placeholder()
    {
        return <<<HTML
            <div class="flex w-full h-16 px-6 items-center rounded-md">
                
                <div class="animated-pulse">
                    <div class="flex flex-row gap-2">
                            <div class="h-6 w-8 bg-neutral-500 rounded-md"></div>
                            <div class="w-28 flex flex-col space-y-1">
                                <div class="h-3 w-44 bg-neutral-500 rounded-full"></div>
                                <div class="h-2 w-28 bg-neutral-500 rounded-full"></div>           
                            </div>
                        </div>                   
                 </div> 
            </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
