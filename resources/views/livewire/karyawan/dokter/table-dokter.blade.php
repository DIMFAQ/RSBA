<div>
    {{ $this->table }}


    {{-- Modal new dokter --}}
    <x-filament::modal id="new-dokter" width="xl" :autofocus="false" :close-by-clicking-away='false'>
        <x-slot name="heading">
            Tambah Dokter Baru
        </x-slot>
        <livewire:Karyawan.Dokter.Add :key="Str::random()" @new-dokter-created="$refresh" />
    </x-filament::modal>
</div>
