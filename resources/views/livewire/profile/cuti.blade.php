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
</div>
