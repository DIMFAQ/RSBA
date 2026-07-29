<?php

namespace App\Livewire\Master\BagianKoordinator;

use Throwable;
use Livewire\Component;
use App\Models\Sdm\BagianKoordinator;
use App\Models\Sdm\Bagian;
use App\Models\Sdm\Karyawan;
use App\Models\User;
use Livewire\Attributes\Lazy;
use TallStackUi\Traits\Interactions;

#[Lazy]
class Add extends Component
{
    use Interactions;

    public $bagian_id;
    public $karyawan_id;
    public $user_id = null; // user login yang akan jadi koordinator (opsional)
    public $aktif = true;

    protected $rules = [
<<<<<<< HEAD
        'bagian_id' => 'required|exists:bagian,id',
=======
        'ruangan_id'  => 'required|exists:ruangan,id',
>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)
        'karyawan_id' => 'required|exists:sdm_karyawan,id',
        'user_id'     => 'nullable|exists:users,id',
        'aktif'       => 'boolean',
    ];

    /**
     * Jika karyawan dipilih, auto-suggest user yang punya karyawan_id sama
     */
    public function updatedKaryawanId($value)
    {
        if ($value) {
            $user = User::where('karyawan_id', $value)->first();
            $this->user_id = $user?->id;
        }
    }

    public function submit()
    {
        $this->validate();

        $exists = BagianKoordinator::where('bagian_id', $this->bagian_id)
            ->where('karyawan_id', $this->karyawan_id)
            ->exists();

        if ($exists) {
            $this->toast()->error('Error', 'Karyawan tersebut sudah ditugaskan sebagai koordinator di bagian ini.')->send();
            return;
        }

        try {
<<<<<<< HEAD
            BagianKoordinator::create([
                'bagian_id' => $this->bagian_id,
=======
            RuanganKoordinator::create([
                'ruangan_id'  => $this->ruangan_id,
>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)
                'karyawan_id' => $this->karyawan_id,
                'user_id'     => $this->user_id ?: null,
                'aktif'       => $this->aktif,
            ]);

<<<<<<< HEAD
            $this->dispatch('new-bagian-koordinator-created');
            $this->dispatch('close-modal', id: 'new-bagian-koordinator');

            $this->toast()->success('Berhasil', 'Koordinator Bagian berhasil ditambahkan.')->send();
            
            $this->reset(['bagian_id', 'karyawan_id']);
=======
            // Hapus cache sidebar/permissions user jika ada akun login
            if ($this->user_id) {
                \Illuminate\Support\Facades\Cache::forget('user-sidebar-menu:' . $this->user_id);
                \Illuminate\Support\Facades\Cache::forget('user-permissions:view:' . $this->user_id);
            }

            $this->dispatch('new-ruangan-koordinator-created');
            $this->dispatch('close-modal', id: 'new-ruangan-koordinator');

            $this->toast()->success('Berhasil', 'Koordinator Ruangan berhasil ditambahkan.')->send();

            $this->reset(['ruangan_id', 'karyawan_id', 'user_id']);
>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)
            $this->aktif = true;
        } catch (Throwable $e) {
            $this->toast()->error('Error', 'Failed : ' . $e->getMessage())->send();
        }
    }

    public string $kategoriFilter = 'all';

    public function render()
    {
        $karyawanQuery = Karyawan::with('dokterRecord.spesialis')
            ->where('resign', null);

        if ($this->kategoriFilter === 'dokter') {
            $karyawanQuery->whereHas('dokterRecord');
        } elseif ($this->kategoriFilter === 'non_dokter') {
            $karyawanQuery->whereDoesntHave('dokterRecord');
        }

        $karyawanOptions = $karyawanQuery->get()->map(function ($k) {
            $isDokter = $k->dokterRecord ? true : false;
            $spesialis = $k->dokterRecord?->spesialis?->nama;
            $tag = $isDokter ? " [DOKTER" . ($spesialis ? " - $spesialis" : "") . "]" : " [KARYAWAN]";

            return [
                'value' => $k->id,
                'label' => $k->full_nama . $tag,
            ];
        })->toArray();

        return view('livewire.master.bagian-koordinator.add', [
<<<<<<< HEAD
<<<<<<< HEAD
            'bagianOptions' => Bagian::select('id', 'nama')->get()->map(fn($item) => ['value' => $item->id, 'label' => $item->nama])->toArray(),
=======
            'ruanganOptions'  => Ruangan::select('id', 'nama')->get()->map(fn($item) => ['value' => $item->id, 'label' => $item->nama])->toArray(),
>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)
            'karyawanOptions' => Karyawan::select('id', 'nama')->get()->map(fn($item) => ['value' => $item->id, 'label' => $item->nama])->toArray(),
=======
            'ruanganOptions'  => Ruangan::select('id', 'nama')->where('is_active', true)->orderBy('nama')->get()->map(fn($item) => ['value' => $item->id, 'label' => $item->nama])->toArray(),
            'karyawanOptions' => $karyawanOptions,
>>>>>>> 5e91fa1 (feat(dokter): penyesuaian koordinator ruangan dan master bagian koordinator)
            'userOptions'     => User::with('karyawan')->get()->map(fn($u) => ['value' => $u->id, 'label' => $u->email . ($u->karyawan ? ' — ' . $u->karyawan->nama : '')])->toArray(),
        ]);
    }
}
