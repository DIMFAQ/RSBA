<div>
    {{ $this->table }}


    <x-filament::modal id="modal-so-input" :close-by-clicking-away="false" :autofocus="false" width="screen">
        <x-slot:heading>Stok Opname</x-slot:heading>

        <livewire:StokOpname.Input :id="$selectedId" :key="'so-input-' . $selectedId" />
    </x-filament::modal>

    <x-filament::modal id="modal-so-investigasi" :close-by-clicking-away="false" :autofocus="false" width="7xl">
        <x-slot:heading>Investigasi Opname</x-slot:heading>

        <livewire:StokOpname.Investigasi :id="$selectedId" :key="'so-investigasi-' . $selectedId" @opname-validasi-saved="$refresh" />
    </x-filament::modal>
</div>
