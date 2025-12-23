<div x-data="gudang" class="flex flex-col gap-2">
    <div class="relative flex w-full flex-row rounded-md bg-white px-4 py-2">
        <span class="flex items-center text-lg italic text-indigo-500" x-text="panelHeading"></span>


        <div class="ml-auto flex justify-end gap-2">
            <x-ts:button outline color="violet" x-show="panelGudang" sm x-on:click="togglePanel('panelPermintaan')">
                <x-slot:left>
                    <x-ts:badge color="violet" :text="rand(1, 100)" round light />
                </x-slot:left>
                Permintaan
            </x-ts:button>


            <x-ts:button x-show="panelGudang" sm icon="tabler.shopping-cart-plus" x-on:click="showOptionsBeli = true">Pembelian</x-ts:button>

            <span role="button" x-show="!panelGudang" x-on:click="togglePanel('panelGudang')" class="flex flex-row items-center px-2 py-1 text-red-500 hover:rounded-lg hover:bg-red-200/25">
                <x-ts:icon name="tabler.chevron-left" class="h-5 w-5" />
                Kembali
            </span>

            <x-ts:button x-show="panelGudang" x-on:click="togglePanel('panelDistribusi')" sm color="red" icon="tabler.shopping-cart-share">Distribusi</x-ts:button>


            <div class="absolute right-0 z-50 mt-2 w-48 rounded-lg bg-white p-4 shadow-lg" x-show="showOptionsBeli" x-transition x-trap.noscroll="showOptionsBeli"
                x-on:click.away="showOptionsBeli = false" x-on:keydown.escape.window="showOptionsBeli = false">
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

    <div x-show="panelGudang" class="flex flex-col gap-2">
        <div class="rounded-md border-2 border-white p-1">
            <x-ts:toggle sm wire:model.live.debounce='stats' label="Stats" />
            @if ($stats)
                <livewire:Gudang.stats :key="Str::random()" />
            @endif
        </div>
        <div class="w-full rounded-lg bg-white p-4">
            <livewire:Gudang.TableGudang :key="Str::random()" />
        </div>
    </div>

    <div x-show="panelPermintaan" class="rounded-md bg-white px-4 py-2">
        <livewire:Pembelian.Permintaan.ListPermintaan :key="Str::random()" />
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
                panelPermintaan: false,
                panelHeading: '',

                togglePanel(panelShow) {
                    this.panelGudang = false;
                    this.panelDistribusi = false;
                    this.panelPembelianPO = false;
                    this.panelPembelianLangsung = false;
                    this.panelPermintaan = false;
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
                    } else if (this.panelPermintaan) {
                        this.panelHeading = 'Permintaan Pengadaan';
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
