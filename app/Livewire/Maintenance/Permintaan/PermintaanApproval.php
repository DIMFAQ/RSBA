<?php

namespace App\Livewire\Maintenance\Permintaan;

use Throwable;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;

#[Lazy]
class PermintaanApproval extends Component
{
    use Interactions;

    public ?object $maintenanceRequest;

    public ?string $approval = null, $priority = 'normal';
    public ?string $jadwal;
    public ?array $teknisi_id = [];
    public ?string $catatan_teknisi = null, $ket_reject = null;

    public $priorityOptions = [
        ['value' => 'normal', 'label' => 'Normal',],
        ['value' => 'penting', 'label' => 'Penting',],
        ['value' => 'darurat', 'label' => 'Darurat',]
    ];

    public function rules(): array
    {
        return [
            'approval' => 'required',
            'jadwal' => $this->approval === 'approved' ? 'required' : 'nullable',
            'teknisi_id' => $this->approval === 'approved' ? 'required' : 'nullable',
            'ket_reject' => $this->approval === 'rejected' ? 'required' : 'nullable'
        ];
    }

    public function mount($maintenanceRequest): void
    {
        $this->maintenanceRequest = $maintenanceRequest;
        $this->priority = $maintenanceRequest->priority ?? 'normal';
    }

    public function submit()
    {

        $this->validate();
        $is_setuju  = $this->approval === 'approved' ? 'Disetujui' : 'Ditolak';

        DB::beginTransaction();
        try {

            // message 

            // 01 Update status permintaan maintenance
            $this->maintenanceRequest->update([
                'status' => $this->approval,
                'user_verify_id' => auth()->id(),
                'ket_reject' => $this->ket_reject,
            ]);

            // 02 Add jadwal dan teknisi jika disetujui
            if ($this->approval === 'approved') {
                $jadwal =  $this->maintenanceRequest->jadwal()->create([
                    'asset_id' => $this->maintenanceRequest->asset_id,
                    'maintc_request_id' => $this->maintenanceRequest->id,
                    'tanggal' => $this->jadwal,
                    'priority' => $this->priority,
                    'note' => $this->catatan_teknisi,
                ]);


                // Assign teknisi
                $teknisiMapping = collect($this->teknisi_id)->map(function ($value, $index) {
                    return [
                        'teknisi_id' => $value,
                        'role' => $index === 0 ? 'leader' : 'helper',
                    ];
                })->toArray();

                // Create teknisi for the jadwal
                $jadwal->teknisi()->createMany(
                    $teknisiMapping
                );
            }

            // 03 Commit transaction
            DB::commit();

            $this->dispatch('submit-approval-beli-request');

            $this->toast()
                ->success('Berhasil', "Permintaan maintenance {$is_setuju}.")
                ->send();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->toast()
                ->success('Terjadi Kesalahan', "<i>{$e->getMessage()}</i> <br> Silahkan coba lagi.")
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.maintenance.permintaan.permintaan-approval');
    }
}
