<?php

namespace App\Livewire\Karyawan;

use Throwable;
use Livewire\Component;
use App\Models\Sdm\Jabatan;
use App\Models\Sdm\Karyawan;
use App\Enums\StatusKaryawan;
use App\Livewire\Forms\KaryawanForm;
use App\Models\Sdm\KaryawanJabatan;
use Livewire\Attributes\Lazy;
use Illuminate\Validation\Rule;
use TallStackUi\Traits\Interactions;

#[Lazy]
class EditKedinasan extends Component
{
    use Interactions;

    public KaryawanForm $form;

    public $status_options;
    public $status_init;
    public $jabatan_options;
    public $jabatan_init;

    public $dinas_options = [
        ['id' => 'resign', 'label' => 'Resign / Mengundurkan Diri'],
        ['id' => 'dipecat', 'label' => 'Dipecat'],
        ['id' => 'end_kontrak', 'label' => 'Habis Kontrak'],
    ];
    public $dinas_init;
    public $ruangan_init;
    public $dinas;
    public $tgl_dinas;


    public function rules(): array
    {
        return [
            'form.status' => 'required',
            'form.jabatan' => 'required',
            'form.tgl_status' => Rule::requiredIf(fn() => $this->form->status != $this->status_init),
            'form.tgl_jabatan' => Rule::requiredIf(fn() => $this->form->jabatan != $this->jabatan_init),
            'form.tgl_ruangan' => Rule::requiredIf(fn() => $this->form->ruangan != $this->ruangan_init),
            'form.tgl_dinas' => Rule::requiredIf(fn() => $this->form->dinas != $this->dinas_init)
        ];
    }

    public function mount($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $this->form->mount($karyawan); //new instance form

        $this->form->setKedinasan($karyawan);

        $this->status_options = StatusKaryawan::options();
        $this->status_init = $karyawan->status;

        $this->jabatan_options = Jabatan::all();
        $this->jabatan_init = $karyawan->jabatan[0]->id ?? '';
        $this->ruangan_init = $karyawan->ruangan_id ?? '';
    }

    public function update()
    {
        $this->validate($this->rules());

        if ($this->form->tgl_status && ($this->form->status != $this->status_init)) {
            $this->updateStatus();
        }

        // update jabatan
        if ($this->form->tgl_jabatan && ($this->form->jabatan != $this->jabatan_init)) {
            $this->updateJabatan();
        }

        // update ruangan
        if ($this->form->tgl_ruangan && ($this->form->ruangan != $this->ruangan_init)) {
            $this->updateRuangan();
        }
    }

    function updateStatus()
    {

        try {
            $data = [
                'status' => $this->form->status
            ];

            // update
            $this->form->karyawan->update($data);

            // event
            $this->dispatch('status-updated');

            // toast
            $this->toast()
                ->success('Sukses', 'Update data kedinasan berhasil.')
                ->send();
        } catch (Throwable $th) {
            $this->toast()
                ->error('Failed', 'Error : ', $th->getMessage())
                ->send();
        }
    }

    public function updateJabatan()
    {
        $latestJabatan = $this->form->karyawan->jabatan?->first();
        try {
            // update tgl_berakhir jabatan terakhir
            if (!empty($latestJabatan)) {
                KaryawanJabatan::where('id', $latestJabatan->pivot->id)
                    ->update(['tgl_berakhir' => $this->form->tgl_jabatan]);
            }

            $data = [
                'jabatan_id' => $this->form->jabatan,
                'karyawan_id' => $this->form->karyawan->id,
                'tgl_mulai' => $this->form->tgl_jabatan
            ];

            // insert data new jabatan
            KaryawanJabatan::create($data);

            $this->dispatch('new-jabatan-created'); //dispatch event

            $this->toast()
                ->success('Berhasil', 'Jabatan baru berhasil disimpan.')
                ->send();
        } catch (Throwable $th) {
            $this->toast()
                ->error('Failed', 'Error : ', $th->getMessage())
                ->send();
        }
    }

    public function updateRuangan()
    {
        try {
            $karyawan = $this->form->karyawan;
            $newRuanganId = $this->form->ruangan;
            $tglRuangan = $this->form->tgl_ruangan;

            // Update tgl_berakhir penugasan ruangan aktif terdahulu
            \App\Models\Sdm\KaryawanRuangan::where('karyawan_id', $karyawan->id)
                ->whereNull('tgl_berakhir')
                ->update(['tgl_berakhir' => $tglRuangan]);

            // Insert penugasan ruangan baru ke sdm_kary_ruangan
            \App\Models\Sdm\KaryawanRuangan::create([
                'karyawan_id' => $karyawan->id,
                'ruangan_id'  => $newRuanganId,
                'tgl_mulai'   => $tglRuangan,
                'tgl_berakhir'=> null,
                'is_utama'    => true,
                'keterangan'  => 'Rotasi / Perubahan Ruangan via Edit Kedinasan',
            ]);

            // Update ruangan_id pada sdm_karyawan
            $karyawan->update(['ruangan_id' => $newRuanganId]);

            $this->ruangan_init = $newRuanganId;
            $this->dispatch('new-ruangan-created');

            $this->toast()
                ->success('Berhasil', 'Penugasan ruangan baru berhasil disimpan.')
                ->send();
        } catch (Throwable $th) {
            $this->toast()
                ->error('Gagal', 'Error : ' . $th->getMessage())
                ->send();
        }
    }


    public function render()
    {
        return view('livewire.karyawan.edit-kedinasan');
    }
}
