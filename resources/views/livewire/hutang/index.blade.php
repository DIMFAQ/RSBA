<div class="flex flex-col gap-2">
    <div class="flex w-full flex-col items-center justify-between gap-3 rounded-lg bg-white px-4 py-2 lg:grid lg:grid-cols-6">
        <x-ts:date wire:model.live.debounce.300='periode' month-year-only placeholder="Periode Pembelian" />
        <x-ts:select.styled wire:model.live.debounce.300='vendor' :request="route('api.supplier')" select="label:nama|value:id" placeholder="Vendor / Supplier" />
        <x-ts:select.styled :options="$optionsFaktur" select="label:label|value:value" placeholder="Jenis Pembelian" />
        <x-ts:date wire:model.live.debounce.300='due_date' placeholder="Jatuh Tempo" />

        <x-tabler-filter-x role="button" class="text-indigo-400 hover:text-red-500" />
    </div>

    <div class="flex w-full flex-col gap-2 lg:grid lg:grid-cols-3">
        <x-ts:stats color="blue" icon="tabler.file-percent" title="Total Periode Ini" :number="formatRupiah($hutang, true, false)" />

        <x-ts:stats color="green" icon="tabler.checklist" title="Telah Dibayar" :number="formatRupiah($dibayar, true, false)" />

        <x-ts:stats color="red" icon="tabler.file-alert" title="Belum Dibayar" :number="formatRupiah($belumDibayar, true, false)" />
    </div>

    {{-- table list hutang --}}
    <div class="w-full rounded-lg bg-white px-4 py-2">

        <livewire:Hutang.ListHutang :periode="$periode" :supplier="$vendor" :key="Str::random()" />
    </div>


    {{-- modal new invoice --}}
    <x-filament::modal id="modal-new-invoices" width="w-11/12">
        <x-slot:heading>Inovices Baru</x-slot:heading>

    </x-filament::modal>
</div>
