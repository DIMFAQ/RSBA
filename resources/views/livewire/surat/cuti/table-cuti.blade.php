<div>
    {{ $this->table }}

    <x-filament::modal id="detil-surat-cuti">
        <x-slot name="heading">
            Detil Tanggal Cuti
        </x-slot>

        <livewire:Surat.Cuti.DetilTanggalCuti :$surat :key="$surat?->id">
    </x-filament::modal>

    <x-filament::modal id="modal-approval-cuti" width="3xl" :close-by-clicking-away="false">
        <x-slot name="heading">
            Persetujuan Surat Cuti
        </x-slot>
        <livewire:Surat.Cuti.Approval :suratCuti="$surat" :key="Str::random()" />
    </x-filament::modal>

</div>
