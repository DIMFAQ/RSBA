<?php

namespace App\Livewire\Public;

use App\Models\Maintenance\PublicReport;
use App\Models\Ruangan;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class PengaduanForm extends Component
{
    use Interactions;

    public int|string $ruangan_id = '';
    public string $jenis = 'umum';
    public string $deskripsi = '';
    public bool $submitted = false;

    public array $jenisOptions = [
        ['value' => 'umum', 'label' => '🔧 Umum (Kerusakan Fisik / Fasilitas)'],
        ['value' => 'it',   'label' => '💻 IT (Komputer / Jaringan / Printer)'],
    ];

    protected function rules(): array
    {
        return [
            'ruangan_id' => 'required|exists:ruangan,id',
            'jenis'      => 'required|in:umum,it',
            'deskripsi'  => 'required|string|min:10|max:1000',
        ];
    }

    protected function messages(): array
    {
        return [
            'ruangan_id.required' => 'Silakan pilih ruangan terlebih dahulu.',
            'ruangan_id.exists'   => 'Ruangan yang dipilih tidak valid.',
            'jenis.required'      => 'Silakan pilih jenis kerusakan.',
            'deskripsi.required'  => 'Deskripsi kerusakan wajib diisi.',
            'deskripsi.min'       => 'Deskripsi minimal 10 karakter.',
            'deskripsi.max'       => 'Deskripsi maksimal 1000 karakter.',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        PublicReport::create([
            'ruangan_id' => $this->ruangan_id,
            'jenis'      => $this->jenis,
            'deskripsi'  => $this->deskripsi,
            'status'     => 'pending',
        ]);

        $this->submitted = true;
        $this->reset(['ruangan_id', 'jenis', 'deskripsi']);
        $this->jenis = 'umum';
    }

    public function resetForm(): void
    {
        $this->submitted = false;
    }

    public function render()
    {
        $ruangans = Ruangan::orderBy('nama')->get()->map(fn($r) => [
            'value' => $r->id,
            'label' => $r->nama,
        ])->toArray();

        return view('livewire.public.pengaduan-form', compact('ruangans'))
            ->layout('components.layouts.guest');
    }
}
