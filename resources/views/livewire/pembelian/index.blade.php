<div class="flex flex-col gap-2">

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
                <div x-data="{
                    showOptionsBeli: false,
                    selectedCaraBeli: null,
                    validationErrors: {},
                    validate() {
                        // Reset previous errors
                        this.validationErrors = {};
                
                        // Validation rules 
                        // jika selectedCarBeli tidak ada, set error
                        if (!this.selectedCaraBeli) {
                            this.validationErrors.caraBeli = 'Cara pembelian harus dipilih.';
                        }
                
                        // Return true if no errors, false otherwise
                        return Object.keys(this.validationErrors).length === 0;
                    },
                    confirmSelectBeli() {
                        // validation
                        if (!this.validate()) {
                            return;
                        }
                
                        {{-- Setting modal id --}}
                        const modalId = `modal-new-pembelian-${this.selectedCaraBeli}`;
                
                        // open modal filament
                        this.$dispatch('open-modal', { id: modalId });
                
                        // Close options
                        this.showOptionsBeli = false;
                    }
                }" class="relative flex flex-row gap-2">

                    <x-ts:button sm outline color="violet" icon="tabler.send">
                        Permintaan
                    </x-ts:button>

                    <x-ts:button sm icon="tabler.plus" x-on:click="showOptionsBeli = true">
                        Pembelian
                    </x-ts:button>

                    <!-- Tooltip Modal -->
                    <div class="absolute right-0 z-50 mt-2 w-60 rounded-lg bg-white p-4 shadow-lg" x-show="showOptionsBeli" x-transition x-trap.noscroll="showOptionsBeli"
                        x-on:click.away="showOptionsBeli = false" x-on:keydown.escape.window="showOptionsBeli = false">

                        <!-- Tooltip Header -->
                        <div class="mb-3 flex items-center justify-between">
                            <span class="flex flex-row items-center gap-1 font-semibold text-indigo-500">
                                <x-ts:icon name="tabler.shopping-cart-plus" class="h-4" />
                                Cara Pembelian
                            </span>
                        </div>

                        <!-- Options -->
                        <div class="space-y-2">
                            <!-- Options Beli -->
                            <div class="flex flex-col gap-2">
                                <template x-if="validationErrors.caraBeli">
                                    <label class="text-xs text-red-500" x-text="validationErrors.caraBeli"></label>
                                </template>

                                <label role="button">
                                    <input type="radio" x-model="selectedCaraBeli" value="langsung" x-on:change="validationErrors.caraBeli = null" />
                                    Langsung
                                </label>

                                <label role="button">
                                    <input type="radio" x-model="selectedCaraBeli" value="pre-order" x-on:change="validationErrors.caraBeli = null" />
                                    Pre Order
                                </label>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex justify-end gap-2">
                            <x-ts:button sm x-on:click="confirmSelectBeli()" icon="tabler.corner-down-right-double">
                                Lanjutkan
                            </x-ts:button>
                        </div>
                    </div>
                    {{-- end Tooltip Modal --}}

                </div>
                {{-- end parent & alpine init --}}

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

    <div class="w-full rounded-md border-2 border-white p-1" x-data="{ showStats: false, refreshKey: Date.now() }">
        <x-ts:toggle sm @click='showStats = !showStats; refreshKey = Date.now()' label="Stats" />

        <div x-show="showStats">
            <livewire:pembelian.stats x-bind:key="'stats-' + refreshKey" />
        </div>
    </div>

    {{-- Table List Pembelian --}}
    <div class="w-full">

        <x-ts:tab :selected="$this->getRequestPembelianProperty() ? 'Permintaan' : 'Transaksi'" x-on:navigate="$wire.set('tab',$event.detail.select)">

            @if ($this->getRequestPembelianProperty())
                <x-ts:tab.items tab="Permintaan">
                    <x-slot:left>
                        <span class="block h-1 w-1 animate-pulse rounded-full bg-red-500 ring-2 ring-red-300"></span>
                    </x-slot:left>

                    <livewire:Pembelian.Permintaan.ListPermintaanBarang :key="Str::random()" />
                </x-ts:tab.items>
            @endif

            <x-ts:tab.items tab="Transaksi">
                <x-slot:left>
                    <x-ts:icon name="tabler.invoice" class="h-5 w-5" />
                </x-slot:left>

                <livewire:Pembelian.TablePembelian :key="Str::random()" />
            </x-ts:tab.items>

            <x-ts:tab.items tab="Barang">
                <x-slot:left>
                    <x-ts:icon name="tabler.box" class="h-5 w-5" />
                </x-slot:left>

                <livewire:Pembelian.TablePembelianBarang :key="Str::random()" />
            </x-ts:tab.items>
        </x-ts:tab>
    </div>


    {{-- modal tambah pembelian langsung --}}
    <x-filament::modal id="modal-new-pembelian-langsung" width="w-full" :close-by-clicking-away="false" :autofocus="false" :close-by-escaping="false" :close-button="false" sticky-header>
        <x-slot:heading>Pembelian Langsung</x-slot:heading>

        <livewire:Pembelian.TransaksiBeliLangsung :key="Str::random()" @close-modal="$refresh" @new-transaksi-langsung-created="$refresh; $dispatch('close-modal','modal-new-pembelian-langsung')" />
    </x-filament::modal>


    {{-- modal tambah pembelian pre order --}}
    <x-filament::modal id="modal-new-pembelian-pre-order" width="w-full" :close-by-clicking-away="false" :autofocus="false" :close-by-escaping="false" :close-button="false" sticky-header>
        <x-slot:heading>Pembelian Pre Order</x-slot:heading>

        <livewire:Pembelian.TransaksiBeliPO :key="Str::random()" @close-modal="$refresh" @new-transaksi-po-created="$refresh; $dispatch('close-modal','modal-new-pembelian-pre-order')" />
    </x-filament::modal>
</div>
