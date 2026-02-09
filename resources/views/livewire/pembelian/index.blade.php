<div x-data="pembelian" class="flex flex-col gap-2">
    <div x-data="{
        searchTerm: @entangle('search'),
    
        init() {
            this.$nextTick(() => {
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            });
        },
    
        reset() {
            this.searchTerm = '';
        },
    }">
        <div class="flex flex-row rounded-lg bg-white px-4 py-2">

            @can('terima-pembelian')
                {{-- search input --}}
                <div class="flex w-full flex-row items-center gap-2">

                    <div class="relative w-3/4 lg:w-1/3">
                        <!-- Input Field -->
                        <input x-ref="searchInput" wire:model.live.debounce.300ms='search' placeholder="Cari No. Transaksi, No. Invoice"
                            class="h-8 w-full rounded-lg border-gray-200 px-10 transition-all duration-300 focus:outline-none" autocomplete="off" />

                        <!-- Icon (Search) -->
                        <x-ts:icon name="tabler.scan" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 transform text-gray-400" />

                        {{-- clear icon --}}
                        <button x-show="searchTerm" @click="reset" class="absolute right-3 top-1/2 -translate-y-1/2 transform text-red-500 hover:text-red-600" type="button">
                            <x-ts:icon name="tabler.x" class="h-4 w-4" />
                        </button>

                    </div>
                </div>
            @endcan


            <div class="ml-auto flex justify-end gap-2">

                <div class="flex gap-2">
                    <x-ts:button x-show="history.length === 0" sm outline color="violet" x-on:click="$wire.set('state',Math.random().toString(36).substring(2, 5)); togglePanel('pesanan')"
                        icon="tabler.file-plus">
                        Pesanan
                    </x-ts:button>

                    <x-ts:button x-show="history.length === 0" sm outline icon="tabler.playlist-add" x-on:click="togglePanel('penerimaan')">
                        Penerimaan
                    </x-ts:button>
                </div>

                <span role="button" x-show="history.length > 0" x-on:click="goBack()" class="flex flex-row items-center px-2 py-1 text-red-500 hover:rounded-lg hover:bg-red-200/25">
                    <x-ts:icon name="tabler.chevron-left" class="h-5 w-5" />
                    Kembali
                </span>
                {{-- <x-ts:button x-show="history.length > 0" x-on:click="goBack()" sm outline color="red" class="">Kembali</x-ts:button> --}}
            </div>
        </div>


        {{-- Hasil Cari Untuk Penerimaan Barang --}}
        <div x-show="searchTerm" @keyup.escape.window="searchTerm = ''" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95" class="relative">

            <div class="absolute inset-0 left-0 z-10">
                <div class="relative mt-1 transform rounded-md border border-b-4 border-indigo-500 bg-white p-4 shadow-2xl transition-transform">
                    <div class="absolute left-[3%] top-[-12px] mb-1 h-0 w-0 -translate-x-1/2 transform border-b-8 border-l-8 border-r-8 border-transparent border-b-indigo-500">
                    </div>
                    {{-- content --}}

                    <div class="mb-2">
                        <span class="italic text-gray-500" wire:loading wire:target='search'> Searching : </span>
                        <span class="italic text-gray-500" wire:loading.remove> Hasil Pencarian : </span>
                        <span class="font-semibold text-indigo-500" x-text="searchTerm"></span>
                    </div>

                    <div wire:loading wire:target="search" class="text-sm italic text-gray-400">
                        Loading ...
                    </div>

                    <div wire:loading.remove>
                        @if ($pembelian)
                            <livewire:Pembelian.Cari :pembelian="$pembelian" :key="Str::random()" />
                        @else
                            <span class="text-sm text-danger-500">Data tidak ditemukan. </span>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>



    {{-- Table List Pembelian --}}
    <div x-show="panelActive === 'main'" class="w-full">
        <div class="w-full rounded-md border-2 border-white p-1" x-data="{ showStats: false, refreshKey: Date.now() }">
            <x-ts:toggle sm @click='showStats = !showStats; refreshKey = Date.now()' label="Stats" />

            <div x-show="showStats">
                <livewire:pembelian.stats x-bind:key="'stats'" />
                {{-- x-bind:key="'stats-' + refreshKey" --}}
            </div>
        </div>


        <x-ts:tab :selected="$this->getRequestPembelianProperty() ? 'Permintaan' : 'Transaksi'" x-on:navigate="$wire.set('tab',$event.detail.select)">

            @if ($this->getRequestPembelianProperty())
                <x-ts:tab.items tab="Permintaan">
                    <x-slot:left>
                        <span class="block h-1 w-1 animate-pulse rounded-full bg-red-500 ring-2 ring-red-300"></span>
                    </x-slot:left>

                    <livewire:Pembelian.Permintaan.ListPermintaanBarang key="list-permintaan-barang" />
                </x-ts:tab.items>
            @endif

            <x-ts:tab.items tab="Transaksi">
                <x-slot:left>
                    <x-ts:icon name="tabler.invoice" class="h-5 w-5" />
                </x-slot:left>

                <livewire:Pembelian.TablePembelian key="table-pembelian" />
            </x-ts:tab.items>

            <x-ts:tab.items tab="Barang">
                <x-slot:left>
                    <x-ts:icon name="tabler.box" class="h-5 w-5" />
                </x-slot:left>

                <livewire:Pembelian.TablePembelianBarang key="table-pembelian-by-barang" />
            </x-ts:tab.items>
        </x-ts:tab>
    </div>


    <div class="w-full rounded-lg bg-white p-4">
        <div x-show="panelActive === 'pesanan'" class="flex flex-col gap-3">
            <h3 class="border-b-2 text-indigo-500">Pesanan</h3>
            <div>
                <livewire:Pembelian.Pesanan key="pesanan" />
            </div>
        </div>
        <div x-show="panelActive === 'penerimaan'" class="flex flex-col gap-3">
            <h3 class="border-b-2 text-indigo-500">Penerimaan</h3>
            <div>
                <livewire:Pembelian.Penerimaan.Options key="penerimaan" />
            </div>
        </div>
    </div>


</div>

@script
    <script>
        Alpine.data('pembelian', () => {
            return {
                panelActive: 'main',
                history: [],

                togglePanel(active) {
                    this.history.push(this.panelActive);
                    this.panelActive = active;
                },

                goBack() {
                    if (this.history.length > 0) {
                        this.panelActive = 'main';
                        this.history = [];

                    }
                }
            }
        })
    </script>
@endscript
