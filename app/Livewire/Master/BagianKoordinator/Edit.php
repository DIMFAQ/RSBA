<?php

namespace App\Livewire\Master\BagianKoordinator;

use Throwable;
use Livewire\Component;
use App\Models\Sdm\BagianKoordinator;
use App\Models\Sdm\Bagian;
use App\Models\Sdm\Karyawan;
use App\Models\User;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use TallStackUi\Traits\Interactions;

#[Lazy]
class Edit extends Component
{
    use Interactions;

    public ?BagianKoordinator $record;

    public $bagian_id;
    public $karyawan_id;
    public $user_id;
    public $aktif;

    #[On('load-bagian-koordinator-data')]
    public function loadData($id)
    {
<<<<<<< HEAD
        $this->record = BagianKoordinator::findOrFail($id);
        $this->bagian_id = $this->record->bagian_id;
        $this->karyawan_id = $this->record->karyawan_id;
        $this->aktif = $this->record->aktif;
=======
        $this->recordId  = $id;
        $record          = RuanganKoordinator::findOrFail($id);
        $this->ruangan_id  = $record->ruangan_id;
        $this->karyawan_id = $record->karyawan_id;
        $this->user_id     = $record->user_id;
        $this->aktif       = $record->aktif;
    }

    /**
     * Jika karyawan diganti, auto-suggest user baru
     */
    public function updatedKaryawanId($value)
    {
        if ($value) {
            $user = User::where('karyawan_id', $value)->first();
            $this->user_id = $user?->id;
        }
>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)
    }

    public function rules()
    {
        return [
<<<<<<< HEAD
            'bagian_id' => 'required|exists:bagian,id',
=======
            'ruangan_id'  => 'required|exists:ruangan,id',
>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)
            'karyawan_id' => 'required|exists:sdm_karyawan,id',
            'user_id'     => 'nullable|exists:users,id',
            'aktif'       => 'boolean',
        ];
    }

    public function submit()
    {
        $this->validate();

        if ($this->record->bagian_id != $this->bagian_id || $this->record->karyawan_id != $this->karyawan_id) {
            $exists = BagianKoordinator::where('bagian_id', $this->bagian_id)
                ->where('karyawan_id', $this->karyawan_id)
                ->exists();

            if ($exists) {
                $this->toast()->error('Error', 'Karyawan tersebut sudah ditugaskan sebagai koordinator di bagian ini.')->send();
                return;
            }
        }

        try {
<<<<<<< HEAD
            $this->record->update([
                'bagian_id' => $this->bagian_id,
=======
            // Hapus cache untuk user lama jika user berubah
            $oldUserId = $record->user_id;

            $record->update([
                'ruangan_id'  => $this->ruangan_id,
>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)
                'karyawan_id' => $this->karyawan_id,
                'user_id'     => $this->user_id ?: null,
                'aktif'       => $this->aktif,
            ]);

<<<<<<< HEAD
            $this->dispatch('bagian-koordinator-updated');
            $this->dispatch('close-modal', id: 'edit-bagian-koordinator');

            $this->toast()->success('Berhasil', 'Koordinator Bagian berhasil diperbarui.')->send();
=======
            // Bersihkan cache sidebar untuk user lama dan baru
            foreach (array_unique(array_filter([$oldUserId, $this->user_id])) as $uid) {
                \Illuminate\Support\Facades\Cache::forget('user-sidebar-menu:' . $uid);
                \Illuminate\Support\Facades\Cache::forget('user-permissions:view:' . $uid);
            }

            $this->dispatch('ruangan-koordinator-updated');
            $this->dispatch('close-modal', id: 'edit-ruangan-koordinator');

            $this->toast()->success('Berhasil', 'Koordinator Ruangan berhasil diperbarui.')->send();
>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)
        } catch (Throwable $e) {
            $this->toast()->error('Error', 'Failed : ' . $e->getMessage())->send();
        }
    }

    public function render()
    {
        return view('livewire.master.bagian-koordinator.edit', [
<<<<<<< HEAD
            'bagianOptions' => Bagian::select('id', 'nama')->get()->map(fn($item) => ['value' => $item->id, 'label' => $item->nama])->toArray(),
=======
            'ruanganOptions'  => Ruangan::select('id', 'nama')->get()->map(fn($item) => ['value' => $item->id, 'label' => $item->nama])->toArray(),
>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)
            'karyawanOptions' => Karyawan::select('id', 'nama')->get()->map(fn($item) => ['value' => $item->id, 'label' => $item->nama])->toArray(),
            'userOptions'     => User::with('karyawan')->get()->map(fn($u) => ['value' => $u->id, 'label' => $u->email . ($u->karyawan ? ' — ' . $u->karyawan->nama : '')])->toArray(),
        ]);
    }
}
