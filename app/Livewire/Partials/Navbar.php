<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Livewire\Auth\Login;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Isolate;
use Illuminate\Support\Facades\Auth;
use TallStackUi\Traits\Interactions;
use Illuminate\Support\Facades\Cache;

#[Lazy]
#[Isolate]
class Navbar extends Component
{
    use Interactions;

    public $title;

    // navbar user data
    public $nama;
    public $email;
    public $foto;
    public $hasFoto;
    public $textFoto;
    public $colorFoto;
    public $isLoading = true;

    public function mount($title)
    {
        $this->title = $title;
        $this->loadUserData();
    }


    function loadUserData()
    {
        $this->isLoading = true;

        $userId = Auth::id();
        $cacheKey = "navbar-user:{$userId}";
        $cacheExp = 60 * 60; //60 menit

        $datas = Cache::remember($cacheKey, $cacheExp, function () {
            $User = Auth::user();
            $karyawan = $User->karyawan;

            return [
                'nama' => $karyawan->nama,
                'email' => $User->email,
                // 'foto' => $karyawan->foto ? asset('storage/' . $karyawan->foto) : null,
                // 'foto' => $karyawan->foto ?? null,
                'foto' => $karyawan->foto ? route('api.users.avatar', ['userId' => $User->id]) : null,
                'has_foto' => !empty($karyawan->foto),
                'text_foto' => $this->getInitials($karyawan->nama),
                'color' => $karyawan->jk === 'L' ? 'indigo' : 'rose'
            ];
        });

        $this->nama = $datas['nama'];
        $this->email = $datas['email'];
        $this->foto = $datas['foto'];
        $this->hasFoto = $datas['has_foto'];
        $this->textFoto = $datas['text_foto'];
        $this->colorFoto = $datas['color'];

        $this->isLoading = false;
    }


    // Initial Nama Karyawan
    function getInitials($name, $length = 2)
    {
        if (empty(trim($name))) return '';

        $words = preg_split('/\s+/', trim($name));
        $initials = '';

        for ($i = 0; $i < min($length, count($words)); $i++) {
            $initials .= substr($words[$i], 0, 1);
        }

        return strtoupper($initials);
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
                    <div class="flex flex-row gap-2 animated-pulse">
                            <div class="h-6 w-8 bg-neutral-300 rounded-md"></div>
                            <div class="w-28 flex flex-col space-y-1">
                                <div class="h-3 w-44 bg-neutral-300 rounded-full"></div>
                                <div class="h-2 w-28 bg-neutral-300 rounded-full"></div>           
                            </div>
                    </div>
                    <div class="ml-auto flex animated-pulse">
                         <div class="flex items-center gap-3">
                            <div class="flex animate-pulse space-x-4">
                                <div class="flex-1 space-y-2 py-1">
                                    <div class="h-4 w-20 rounded bg-gray-300"></div>
                                    <div class="h-3 w-16 rounded bg-gray-300"></div>
                                </div>
                                <div class="h-10 w-10 rounded-full bg-gray-300"></div>
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
