<div>
    <div class="flex flex-col gap-2">
        <div class="ml-auto flex justify-end">
            <x-ts:button sm x-on:click="$dispatch('open-modal',{id:'new-cuti'})" icon="tabler.mail-plus">
                Pengajuan Cuti
            </x-ts:button>
        </div>
        <div>
            {{ $this->table }}
        </div>
    </div>

    <x-filament::modal id="new-cuti" width="lg" :close-by-clicking-away="false">
        <x-slot name="heading">
            Pengajuan Cuti
        </x-slot>

        <livewire:Surat.Cuti.Pengajuan :id="$karyawan->id" :key="Str::random()" @created-cuti="$refresh" />
    </x-filament::modal>

    <x-filament::modal id="view-detil-tanggal" :close-by-clicking-away="false">
        <x-slot name="heading">
            Detil Tanggal Cuti
        </x-slot>

        <livewire:Surat.Cuti.DetilTanggalCuti :$surat :key="$surat?->id" />
    </x-filament::modal>

    <x-filament::modal id="modal-status-cuti">
        <livewire:Surat.Cuti.ViewStatus :$surat :key="'view-status-cuti-' . Str::random(3)" />
    </x-filament::modal>

    <div x-data x-on:trigger-print-cuti.window="$nextTick(() => printArea('print-cuti-approved'))">
        <div class="hidden" id="print-cuti-approved">
            @if ($surat)
                <livewire:Surat.Cuti.PrintCuti :suratCuti="$surat" :key="'print-cuti-' . Str::random(5)" />
            @endif
        </div>
    </div>
</div>
