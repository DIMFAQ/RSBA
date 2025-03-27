<div class="w-full">
    <x-ts:tab selected="Pembelian" x-on:navigate="$wire.set('tab',$event.detail.select)">
        <x-ts:tab.items tab="Pembelian">
            <x-slot:left>
                <x-ts:icon name="tabler.shopping-cart-plus" class="h-5 w-5" />
            </x-slot:left>

            <livewire:Laporan.Umum.Pembelian :key="Str::random()" />
        </x-ts:tab.items>

        <x-ts:tab.items tab="Distribusi">
            <x-slot:left>
                <x-ts:icon name="tabler.shopping-cart-share" class="h-5 w-5" />
            </x-slot:left>

            <livewire:Laporan.Umum.Distribusi :key="Str::random()" />
        </x-ts:tab.items>
    </x-ts:tab>


</div>
