<?php

namespace App\Livewire\Master\Jabatan;

use App\Models\Sdm\Jabatan;
use Livewire\Component;

class OrgChart extends Component
{
    public function getChartDataProperty()
    {
        $jabatans = Jabatan::with([
            'bagian',
            'tingkat',
            'jabatans' => fn ($q) => $q->orderBy('created_at', 'desc'),
            'jabatans.karyawan' => fn ($q) => $q->whereNull('resign')
        ])->get();

        $nodes = [];

        foreach ($jabatans as $j) {
            $karyawan = $j->jabatans->first()?->karyawan;

            // Pastikan Direktur Utama (id = 1 atau level 1) selalu menjadi root node utama
            $parentId = $j->parent_id ? (string) $j->parent_id : null;
            if ((string)$j->id === '1' || (string)$j->id === (string)$j->parent_id) {
                $parentId = null;
            }

            $nodes[] = [
                'id'         => (string) $j->id,
                'parentId'   => $parentId,
                'name'       => $karyawan?->nama ?? 'Vacant / Belum Diisi',
                'position'   => $j->nama ?? '—',
                'department' => $j->bagian?->nama ?? 'RSBA',
                'nip'        => $karyawan?->nip ?? '—',
                'status'     => $karyawan ? ($karyawan->status?->nama() ?? 'Aktif') : 'Kosong',
                'tingkat'    => $j->tingkat?->nama ?? ('Level ' . ($j->tingkat?->urutan ?? 99)),
                'avatar'     => $karyawan?->foto 
                    ? asset('storage/' . $karyawan->foto)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($karyawan?->nama ?? $j->nama) . '&background=6366f1&color=ffffff&bold=true',
            ];
        }

        return $nodes;
    }

    public function render()
    {
        return view('livewire.master.jabatan.org-chart', [
            'chartData' => $this->chartData,
        ]);
    }
}
