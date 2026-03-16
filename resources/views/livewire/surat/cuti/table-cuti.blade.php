<div>
    {{ $this->table }}

    <x-filament::modal id="detil-surat-cuti">
        <livewire:Surat.Cuti.DetilTanggalCuti :$surat :key="'detail-tgl-cuti-' . Str::random(3)">
    </x-filament::modal>


    <x-filament::modal id="modal-status-cuti">
        <livewire:Surat.Cuti.ViewStatus :$surat :key="'view-status-cuti-' . Str::random(3)">
    </x-filament::modal>


    <x-filament::modal id="modal-approval-cuti" width="3xl" :close-by-clicking-away="false" x-on:surat-cuti-approved.window="$dispatch('close-modal',{id:'modal-approval-cuti'})">
        <x-slot name="heading">
            Persetujuan Surat Cuti
        </x-slot>
        <livewire:Surat.Cuti.Approval :suratCuti="$surat" :key="Str::random(5)" @surat-cuti-approved="$refresh" />
    </x-filament::modal>

    <x-filament::modal id="modal-options-approval-manual" width="3xl" :close-by-clicking-away="false" x-on:surat-cuti-manual-approved.window="$dispatch('close-modal',{id:'modal-options-approval-manual'})">
        <x-slot name="heading">
            Print Pengajuan Manual
        </x-slot>
        <livewire:Surat.Cuti.ApprovalManual :suratCuti="$surat" :key="'manual-approve-' . Str::random(3)" @surat-cuti-manual-approved="$refresh" />
    </x-filament::modal>

    <div x-data x-on:trigger-print.window="$nextTick(() => printArea('print-cuti-approved'))">
        <div class="hidden" id="print-cuti-approved">
            @if ($surat)
                <livewire:Surat.Cuti.PrintCuti :suratCuti="$surat" :key="'print-cuti-' . $surat->id" />
            @endif
        </div>
    </div>
</div>
