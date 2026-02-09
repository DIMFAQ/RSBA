<div>
    {{ $this->table }}


    {{-- modal --}}
    <x-filament::modal id="modal-detail-pembelian" width="7xl" :close-on-click-away="false">
        <x-slot:heading>Detail Pembelian</x-slot:heading>

        <livewire:Pembelian.ViewDetailPembelian :id="$selectedId" :key="time() . $selectedId" />
    </x-filament::modal>


    <x-filament::modal id="modal-create-sp3" width="5xl" :close-on-click-away="false">
        <x-slot:heading>Buat SP3 Pembelian</x-slot:heading>

        <livewire:Surat.Sp3.AddSp3Pembelian :id="$selectedId" :key="time() . $selectedId" />
    </x-filament::modal>

    <div x-data x-on:trigger-print.window="$nextTick(() => printArea('print-pre-order'))">
        <div class="hidden" id="print-pre-order">
            @if ($selectedId)
                <livewire:Pembelian.PrintPo :id="$selectedId" :key="'print-po' . $selectedId" />
            @endif
        </div>
    </div>
</div>
