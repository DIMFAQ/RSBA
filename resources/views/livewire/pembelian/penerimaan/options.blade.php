<div x-data="{ panelActive: '', searchTerm: '' }" class="flex flex-col gap-2">
    <span class="text-sm italic text-gray-500">Sumber penerimaan barang dari :</span>
    <div class="flex flex-row gap-4">
        <x-ts:radio id="sumber_hibah" x-model="panelActive" x-on:click="togglePanel('hibah')" value="hibah" label="Hibah" />
        <x-ts:radio id="sumber_pembelian" x-model="panelActive" x-on:click="togglePanel('pembelian')" value="pembelian" label="Pembelian" />
        <x-ts:radio id="sumber_po" x-model="panelActive" x-on:click="togglePanel('preorder'); $nextTick(() =>{$refs.searchInput.focus()} )" value="preorder" label="Pre Order" />
    </div>
    <hr class="border-gray-300 p-2">


    <span x-show="!panelActive" x-cloak class="text-sm italic text-gray-300">Pilih Sumber Penerimaan</span>

    <div x-show="panelActive === 'hibah'">
        <livewire:Pembelian.Penerimaan.Hibah :key="'penerimaan-hibah-' . uniqid()" />
    </div>

    <div x-show="panelActive === 'pembelian'">
        <livewire:Pembelian.TransaksiBeliLangsung :key="Str::random()" @new-transaksi-langsung-created="$refresh" />
    </div>

    {{-- terima pre order --}}
    <div x-show="panelActive === 'preorder'" class="flex flex-col gap-3">
        {{-- search --}}
        <div class="flex w-full flex-row items-center gap-2">
            <div class="relative w-3/4 lg:w-1/3">
                <!-- Input Field -->
                <input x-ref="searchInput" wire:model.live.debounce.300ms='search' placeholder="Cari Nomor PO"
                    class="h-8 w-full rounded-lg border-gray-200 px-10 transition-all duration-300 focus:outline-none" autocomplete="off" />

                <!-- Icon (Search) -->
                <x-ts:icon name="tabler.scan" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 transform text-gray-400" />

                {{-- clear icon --}}
                <button x-show="searchTerm" @click="reset" class="absolute right-3 top-1/2 -translate-y-1/2 transform text-red-500 hover:text-red-600" type="button">
                    <x-ts:icon name="tabler.x" class="h-4 w-4" />
                </button>
            </div>
        </div>
        {{-- end search --}}

        <div class="w-full">
            @if ($pembelian)
                <livewire:Pembelian.Cari :$pembelian :key="'cari-po-' . $this->search" />
            @endif
        </div>
    </div>
    {{-- end terima pre order  --}}
</div>
