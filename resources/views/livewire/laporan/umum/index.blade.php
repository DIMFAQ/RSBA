<div class="w-full">
    <x-ts:tab selected="Pembelian" x-on:navigate="$wire.set('tab',$event.detail.select)">

        {{-- tab pembelian --}}
        <x-ts:tab.items tab="Pembelian">
            <x-slot:left>
                <x-ts:icon name="tabler.shopping-cart-plus" class="h-5 w-5" />
            </x-slot:left>

            <div class="flex flex-col gap-2">
                <form wire:submit.prevent='cariPembelian' class="flex w-full flex-col items-center justify-between gap-3 rounded-lg bg-white px-4 py-2 lg:grid lg:grid-cols-4">
                    <x-ts:date range wire:model.lazy='periode' placeholder="Periode" />
                    <x-ts:select.styled wire:model.lazy='vendor' :request="route('api.supplier')" select="label:nama|value:id" placeholder="Vendor / Supplier" />
                    <x-ts:select.styled wire:model.lazy='jenis' :options="$optionsFaktur" select="label:label|value:value" placeholder="Jenis Pembelian" />

                    <div class="w-10">
                        <x-ts:button sm outline type="submit" icon="tabler.zoom" loading="cariPembelian" position="left">Cari</x-ts:button>
                    </div>
                </form>
                <div class="w-full rounded-lg bg-white px-4 py-2">
                    <livewire:Laporan.Umum.Pembelian key="pembelian.laporan" />
                </div>
            </div>
        </x-ts:tab.items>


        {{-- tab Distribusi --}}
        <x-ts:tab.items tab="Distribusi">
            <x-slot:left>
                <x-ts:icon name="tabler.shopping-cart-share" class="h-5 w-5" />
            </x-slot:left>

            <div class="flex flex-col gap-2">
                <form wire:submit.prevent='cariDistribusi' class="flex w-full flex-col items-center justify-between gap-3 rounded-lg bg-white px-4 py-2 lg:grid lg:grid-cols-4">
                    <x-ts:date range wire:model.lazy='periode' placeholder="Periode Distribusi" />
                    <x-ts:select.styled wire:model.lazy='ruangan' :request="route('api.ruangan')" select="label:nama|value:id" placeholder="Ruangan" />
                    <div class="w-10">
                        <x-ts:button sm outline type="submit" loading="cariDistribusi" icon="tabler.zoom" position="left">Cari</x-ts:button>
                    </div>

                </form>
                <div class="w-full rounded-lg bg-white px-4 py-2">
                    <livewire:Laporan.Umum.Distribusi key="distribusi.laporan" />
                </div>
            </div>
        </x-ts:tab.items>
    </x-ts:tab>


</div>
