<div class="flex w-full flex-col gap-4">
    <div class="grid grid-cols-2 gap-2 lg:grid-cols-6">
        <x-ts:date wire:model.defer='periode' month-year-only />
        <x-ts:select.styled wire:model.defer='cabar' placeholder="Cara Bayar" :options="$cabar_opt" select="label:label|value:value" />
        <x-ts:select.styled wire:model.live.debounce.500='layanan' placeholder="Layanan" :options="$layanan_opt" select="label:label|value:value" />
        <x-ts:select.styled wire:model.defer='kelompok' placeholder="Kelompok" :options="$kelompok_opt" select="label:label|value:value" />
        <x-ts:select.styled wire:model.defer='batch' placeholder="Batch" :options="$batchOptions" select="label:label|value:value" />
        <x-ts:button icon="tabler.search" sm wire:click="cari" wire:loading.attr="disabled" loading="cari">Cari</x-ts:button>
    </div>

    <div>
        <x-ts:tab selected="Prosentase" x-on:navigate="$wire.set('tab',$event.detail.select)">

            <x-ts:tab.items tab="Prosentase">
                <livewire:Jasmed.Verify.ListJasmedVerify :$periode :$layanan :$cabar :$kelompok :$batch :key="'table-list-verify-' . md5($periode . $layanan . $cabar . $kelompok . $batch . $tab)" />
            </x-ts:tab.items>

            <x-ts:tab.items tab="Jasa Dokter">
                <livewire:Jasmed.Verify.ListDokterJasa :$periode :$layanan :$cabar :$kelompok :$batch :key="'jasa-dokters-verify-' . md5($periode . $layanan . $cabar . $kelompok . $batch . $tab)" />
            </x-ts:tab.items>
        </x-ts:tab>
    </div>

</div>
