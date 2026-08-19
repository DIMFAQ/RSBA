<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tracking_code' => $this->tracking_code,
            'jenis'         => $this->jenis,
            'jenis_label'   => $this->jenis === 'it' ? 'IT' : 'Umum',
            'deskripsi'     => $this->deskripsi,
            'status'        => $this->status,
            'status_label'  => match ($this->status) {
                'proses'  => 'Diproses',
                'selesai' => 'Selesai',
                default   => 'Menunggu',
            },
            'pelapor_nama'  => $this->pelapor_nama,
            'ruangan'       => $this->ruangan?->nama,
            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
