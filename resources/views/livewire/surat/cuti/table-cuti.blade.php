<div>
    {{ $this->table }}

    <x-filament::modal id="detil-surat-cuti">
        <x-slot name="heading">
            Detil Tanggal Cuti
        </x-slot>

        <livewire:Surat.Cuti.DetilTanggalCuti :$surat :key="$surat?->id">
    </x-filament::modal>
</div>
