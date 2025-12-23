<div>
    {{ $this->table }}


    <x-filament::modal id="modal-pengajuan-to-langsung" width="w-full">
        <x-slot:heading>Pembelian Langsung</x-slot:heading>

        <livewire:Pembelian.TransaksiBeliLangsung :key="Str::random()" />
    </x-filament::modal>


    <x-filament::modal id="modal-pengajuan-to-pre-order" width="w-full">
        <x-slot:heading>Pembelian Pre Order</x-slot:heading>

        <livewire:Pembelian.TransaksiBeliPO :key="Str::random()" />
    </x-filament::modal>
</div>
