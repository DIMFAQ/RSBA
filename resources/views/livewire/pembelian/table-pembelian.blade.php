<div>
    {{ $this->table }}


    {{-- modal --}}
    <x-filament::modal id="modal-detail-pembelian" width="5xl" :close-on-click-away="false">
        <x-slot:heading>Detail Pembelian</x-slot:heading>

        <livewire:Pembelian.ViewDetailPembelian :id="$selectedId" :key="time() . $selectedId" />
    </x-filament::modal>
</div>
