<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Lazy]
#[Title('Profile')]
class Index extends Component
{
    use WithFileUploads;

    public $userTmp;
    public $user;

    public $tab = 'Home';

    function mount()
    {
        $this->user = Auth::user();
    }

    function updateAvatar()
    {

        $this->validate([
            'profileTmp' => 'required|image|max:1024', // 1MB Max
        ]);

        try {
            $path = $this->logoTmp->store('user', 'public');

            // Perusahaan::where('id', 1)->update(['logo' => $path]);

            $this->toast()->success('Success!', 'Photo profile berhasil diupdate.')->send();
        } catch (\Throwable $e) {
            $this->toast()->error('Failed!', 'Error : ' . $e->getMessage())->send();
        }

        $this->reset('userTmp');
    }

    public function render()
    {
        return view('livewire.profile.index');
    }
}
