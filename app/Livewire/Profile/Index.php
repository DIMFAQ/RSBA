<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Sdm\Karyawan;
use Livewire\Attributes\Lazy;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use TallStackUi\Traits\Interactions;

#[Lazy]
#[Title('Profile')]
class Index extends Component
{
    use Interactions;
    use WithFileUploads;

    public $profileTmp;
    public $user;

    public $tab = 'Home';

    function mount()
    {
        $this->user = Auth::user();
    }

    function updateAvatar()
    {
        $this->validate([
            'profileTmp' => 'required|image|max:250', // 300kb Max
        ]);

        DB::beginTransaction();
        try {
            $path = $this->profileTmp->store('user/profile', 'public');

            Karyawan::where('id', $this->user->karyawan_id)
                ->update(['foto' => $path]);

            $this->toast()
                ->success('Berhasil !', 'Profile foto berhasil diupdate.')
                ->send();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->toast()
                ->error('Gagal Update Profile !', 'Error : ' . $e->getMessage())
                ->send();
        }

        $this->reset('profileTmp');
    }

    public function render()
    {
        return view('livewire.profile.index');
    }
}
