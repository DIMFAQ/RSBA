<div x-data="gudang" class="flex flex-col gap-2">
    <div class="relative flex w-full flex-row rounded-md bg-white px-4 py-2">
        <span class="flex items-center text-lg font-semibold text-indigo-500" x-text="panelHeading"></span>


        <div class="ml-auto flex justify-end gap-2">
            <x-ts:button x-show="!panelPembelianPO && !panelPembelianLangsung" sm icon="tabler.shopping-cart-plus" x-on:click="showOptionsBeli = true">Pembelian</x-ts:button>
            <span role="button" x-show="!panelGudang" x-on:click="togglePanel('panelGudang')" class="flex flex-row items-center px-2 py-1 text-red-500 hover:rounded-lg hover:bg-red-200/25">
                <x-ts:icon name="tabler.chevron-left" class="h-5 w-5" />
                Kembali
            </span>
            <x-ts:button x-show="!panelDistribusi" x-on:click="togglePanel('panelDistribusi')" sm color="red" icon="tabler.shopping-cart-share">Distribusi</x-ts:button>
            <div class="absolute right-0 z-50 mt-2 w-48 rounded-lg bg-white p-4 shadow-lg" x-show="showOptionsBeli" x-transition x-trap.noscroll="showOptionsBeli">
                <!-- Tooltip Header -->
                <div class="mb-3 flex items-center justify-between">
                    <span class="flex flex-row gap-2 font-semibold text-indigo-500">
                        <x-ts:icon name="tabler.shopping-cart-plus" class="h-5 w-5" />
                        Pembelian
                    </span>
                    <button x-on:click="showOptionsBeli = false" class="text-gray-400 hover:text-gray-600">
                        &times;
                    </button>
                </div>
                <!-- Options -->
                <div class="space-y-2">
                    <!-- Options Beli -->
                    <div class="flex flex-col gap-2">
                        <template x-if="validationErrors.caraBeli">
                            <label class="text-xs text-red-500" x-text="validationErrors.caraBeli"></label>
                        </template>
                        <label>
                            <input type="radio" x-model="selectedCaraBeli" value="Langsung" x-on:change="validationErrors.caraBeli = null" />
                            Langsung
                        </label>
                        <label>
                            <input type="radio" x-model="selectedCaraBeli" value="PO" x-on:change="validationErrors.caraBeli = null" />
                            Pre Order
                        </label>
                    </div>
                </div>
                <!-- Actions -->
                <div class="mt-4 flex justify-end gap-2">
                    <x-ts:button sm x-on:click="confirmSelectBeli()">
                        Lanjutkan
                    </x-ts:button>
                </div>
            </div>
        </div>

    </div>

    <div x-show="panelGudang">
        <div class="grid grid-cols-4 gap-2">
            <x-ts:stats icon="tabler.trending-down" color="red" title="Akan Habis" :number="$barangAkanHabis" footer="Barang akan habis" role="button"
                x-on:click="$dispatch('open-modal',{id:'modal-stok-akan-habis'})" />
            <x-ts:stats icon="tabler.stack-back" color="orange" title="Belum disusun" :number="$barangBelumDisusun" footer="Barang belum disusun." />
            <x-ts:stats icon="tabler.shopping-cart-bolt" color="green" title="Fast Moving" :number="$barangFastMoving" footer="Barang sering terpakai 1 bulan terkahir." role="button"
                x-on:click="$dispatch('open-modal',{id:'modal-fast-moving'})" />
            <x-ts:stats icon="tabler.shopping-cart-pause" color="blue" title="Slow Moving" :number="$barangSlowMoving" footer="Barang tidak terpakai 1 bulan terakhir." role="button"
                x-on:click="$dispatch('open-modal',{id:'modal-slow-moving'})" />
            <x-filament::modal id="modal-stok-akan-habis" width="max-w-4xl">
                <x-slot:heading>Data Stok Gudang Akan Habis</x-slot:heading>
                <livewire:Gudang.ViewStokHabis :key="Str::random()" />
            </x-filament::modal>
            <x-filament::modal id="modal-belum-disusun" width="max-w-4xl">
                <x-slot:heading>Data Barang Belum Disusun</x-slot:heading>
                <livewire:Gudang.ViewStokHabis :key="Str::random()" />
            </x-filament::modal>
            <x-filament::modal id="modal-fast-moving" width="max-w-4xl">
                <x-slot:heading>Data Gudang Fast Moving</x-slot:heading>
                <livewire:Gudang.ViewFastMoving :key="Str::random()" />
            </x-filament::modal>
            <x-filament::modal id="modal-slow-moving" width="max-w-4xl">
                <x-slot:heading>Data Gudang Slow Moving</x-slot:heading>
                <livewire:Gudang.ViewSlowMoving :key="Str::random()" />
            </x-filament::modal>
        </div>
        <div class="w-full rounded-lg bg-white p-4">
            <livewire:Gudang.TableGudang :key="Str::random()" />
        </div>
    </div>

    <div x-show="panelPembelianPO" class="rounded-md bg-white px-4 py-2">
        {{-- <h2 class="pb-4 text-lg font-semibold text-indigo-500">Pembelian Pre Order</h2> --}}
        <livewire:Pembelian.TransaksiBeliPO :key="Str::random()" />
    </div>

    <div x-show="panelPembelianLangsung" class="rounded-md bg-white px-4 py-2">
        {{-- <h2 class="pb-4 text-lg font-semibold text-indigo-500">Pembelian Langsung</h2> --}}
        <livewire:Pembelian.TransaksiBeliLangsung :key="Str::random()" />
    </div>

    <div x-show="panelDistribusi" class="rounded-md bg-white px-4 py-2">
        {{-- <h2 class="p-4 text-lg font-semibold text-indigo-500">Distribusi</h2> --}}
        <livewire:Distribusi.Transaksi :key="Str::random()" />
    </div>
</div>

@script
    <script>
        Alpine.data('gudang', () => {
            return {
                panelGudang: true,
                panelDistribusi: false,
                panelPembelianPO: false,
                panelPembelianLangsung: false,
                panelHeading: '',

                togglePanel(panelShow) {
                    this.panelGudang = false;
                    this.panelDistribusi = false;
                    this.panelPembelianPO = false;
                    this.panelPembelianLangsung = false;
                    this[panelShow] = true;

                    this.toggleHeading();
                },

                toggleHeading() {
                    if (this.panelDistribusi) {
                        this.panelHeading = 'Distibusi';
                    } else if (this.panelPembelianLangsung) {
                        this.panelHeading = 'Pembelian Langsung';
                    } else if (this.panelPembelianPO) {
                        this.panelHeading = 'Pembelian Pre Order';
                    } else {
                        this.panelHeading = '';
                    }
                },

                // popup cara pembelian
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

                    let selectedPanel = `panelPembelian${this.selectedCaraBeli}`;
                    this.togglePanel(selectedPanel);;

                    // Close options
                    this.showOptionsBeli = false;
                }

            };
        });
    </script>
@endscript
