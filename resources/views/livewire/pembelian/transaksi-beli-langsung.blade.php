<div>
    {{-- form --}}
    <form wire:submit.prevent='submit' class="flex flex-col gap-2" autocomplete="off">
        {{-- input --}}
        <div class="mb-4 flex w-full flex-col gap-2 lg:flex-row">

            <div class="w-full lg:w-1/3">
                <x-ts:select.styled wire:model.defer='supplier' placeholder="Supplier" :request="route('api.supplier')" select="label:nama|value:id">

                    {{-- button new supplier --}}
                    <x-slot:after>
                        <div class="mb-2 flex items-center justify-center px-2">
                            <x-ts:button sm x-on:click="show = false; $dispatch('open-modal', {id:'modal-new-supplier'}); $wire.set('createTerm',search)">
                                <span x-html="`Create <b>${search}</b>`"></span>
                            </x-ts:button>
                        </div>
                    </x-slot:after>
                </x-ts:select.styled>
            </div>

            <div class="flex w-full flex-col gap-2 lg:grid lg:w-3/4 lg:grid-cols-5">
                <x-ts:date placeholder="Tgl. Pembelian" wire:model.defer='tgl_pembelian' />

                <x-ts:input placeholder="No Faktur / Nota" wire:model.defer='no_faktur' />

                <x-ts:select.styled placeholder="Pembayaran" :options="$cabarOptions" select="label:nama|value:value" wire:model.defer='status_pembayaran' />

                <x-ts:date placeholder="Tgl Pembayaran" wire:model.defer='tgl_pembayaran' />

                <x-ts:input wire:model.defer='keterangan' placeholder="Keterangan" />
            </div>

        </div>

        {{-- list barang --}}
        {{-- listPembelian : Alpine on tags script --}}
        <div x-data="listPembelian" class="flex flex-col gap-2">

            {{-- manage cart --}}
            <div class="relative rounded-lg border border-gray-200">
                <span class="absolute -left-0 -top-3 rounded-sm bg-white px-2 font-semibold text-indigo-500">
                    List Barang Yang Dibeli
                </span>

                <div class="my-2 flex flex-col gap-1 p-2">

                    {{-- cari barang --}}
                    <div x-data="{ useSelect: false }" x-init="$nextTick(() => $refs.barcodeSearch.focus())" class="flex w-1/2 flex-row items-center gap-2 lg:w-1/3">
                        <div class="w-full">
                            <template x-if="useSelect">
                                <x-ts:select.styled x-model.debounce.300ms='searchItem' :request="route('api.barang.ref')" select="label:nama|value:id" placeholder="Pilih barang"
                                    x-on:select="addingCart($event.detail.select.id)">
                                    {{-- $wire.addingCart($event.detail.select.id) --}}

                                    {{-- button adding new --}}
                                    <x-slot:after>
                                        <div class="mb-2 flex items-center justify-center px-2">
                                            <x-ts:button sm x-on:click="show = false; $dispatch('open-modal', {id:'modal-new-barang'}); $wire.set('createTerm',search)">
                                                <span x-html="`Create <b>${search}</b>`"></span>
                                            </x-ts:button>
                                        </div>
                                    </x-slot:after>
                                </x-ts:select.styled>
                            </template>
                            <template x-if="!useSelect">
                                <x-ts:input x-ref="barcodeSearch" x-model="sku" @input.debounce="addingCart(sku)" placeholder="Scan barang" icon="tabler.barcode" />
                            </template>
                        </div>

                        <span x-on:click="useSelect = !useSelect" role="button">
                            <x-tabler-barcode class="h-8 w-8 text-indigo-500" title="Switch using barcode." x-show="useSelect" />
                            <x-tabler-direction class="h-8 w-8 text-indigo-500" title="Switch using select." x-show="!useSelect" />
                        </span>

                    </div>

                    <div class="text-sm text-red-500">
                        @error('cartItems')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>

                    <table class="min-w-full table-fixed border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-100 text-left text-sm text-gray-600">
                                <th class="px-4 py-2">No.</th>
                                <th class="px-4 py-2">SKU</th>
                                <th class="px-4 py-2">Barang</th>
                                <th class="px-4 py-2">Satuan</th>
                                <th class="px-4 py-2">Jumlah Beli</th>
                                <th class="px-4 py-2">Harga Satuan</th>
                                <th class="px-4 py-2">Batch</th>
                                <th class="px-4 py-2">Sub Total</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <template x-for="(item, index) in cartItems" :key="index">
                                <tr class="border-b even:bg-gray-100/75 hover:bg-indigo-100">
                                    <td class="px-4 py-2" x-text="index + 1"></td>
                                    <td class="px-4 py-2" x-text="item.sku"></td>
                                    <td class="px-4 py-2" x-text="item.nama"></td>
                                    <td class="px-4 py-2" x-text="item.satuan"></td>
                                    <td class="px-4 py-2">
                                        <input type="number" x-model.number="item.jumlah" @keyup="recalculateTotal(index)" class="h-8 max-w-24 rounded-lg border border-gray-100" placeholder="Qty" />
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" x-model.number="item.harga" @keyup="recalculateTotal(index)" class="h-8 max-w-32 rounded-lg border border-gray-100"
                                            placeholder="Harga Satuan" />
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="text" x-model="item.batch" class="h-8 max-w-32 rounded-lg border border-gray-100" placeholder="Batch" />
                                    </td>
                                    <td class="px-4 py-2" x-text="item.subTotal.toLocaleString()"></td>
                                    <td class="px-4 py-2">
                                        <x-tabler-trash class="text-red-500" role="button" @click="removeItemFromCart(index)" />
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="cartItems.length === 0">
                                <td colspan="9" class="px-4 py-2 text-center italic text-gray-400">
                                    Belum ada list pembelian barang.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- summary cart --}}
            <div class="grid w-full grid-cols-2 rounded-md border border-gray-200 bg-indigo-100/75 px-4 py-2">
                <div class="flex flex-col">
                    <span class="font-thin italic">Item beli:</span>
                    <span class="text-xl font-bold text-indigo-500" x-text="totalItem"></span>

                </div>
                <div class="justity-end flex flex-col">
                    <span class="font-thin italic">Total :</span>
                    <span class="text-xl font-bold text-indigo-500" x-text="`Rp. ${totalBeli.toLocaleString()}`"></span>
                </div>
            </div>
        </div>

        {{-- actions form --}}
        {{-- TODO: Confirm on cancel form --}}
        <div class="ml-auto flex flex-row justify-end gap-2">

            {{-- cancel confirm popup --}}
            <div x-data="{ popUpCancelConfirm: false }" class="relative">
                <x-ts:button outline color="neutral" x-on:click="popUpCancelConfirm = true">Tutup</x-ts:button>

                <div x-show="popUpCancelConfirm" x-transition x-trap.noscroll="popUpCancelConfirm" class="absolute right-0 z-50 mt-2 max-w-fit rounded-lg bg-white p-4 shadow-lg">

                    <!-- Tooltip Header -->
                    <div class="mb-3 flex items-center justify-between">
                        <span class="flex flex-row items-center gap-2 whitespace-nowrap font-medium text-indigo-500">
                            <x-ts:icon name="tabler.alert-circle" class="h-5 w-5" />
                            Pembelian Dibatalkan ?
                        </span>
                    </div>

                    <!-- Options -->
                    <div class="flex items-center justify-between gap-4">
                        <span class="flex flex-row items-center gap-2 whitespace-nowrap text-sm font-light text-gray-500">
                            Data pada form pembelian akan hilang.
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 flex justify-end gap-2">
                        <x-ts:button outline sm color="red" x-on:click="popUpCancelConfirm = false">
                            Tidak
                        </x-ts:button>
                        <x-ts:button outline sm color="green" x-on:click="$dispatch('close-modal',{id:'modal-new-pembelian-langsung'})">
                            Ya, Batalkan
                        </x-ts:button>
                    </div>
                </div>

            </div>
            <x-ts:button type="submit" loading="submit" icon="tabler.checks">Simpan</x-ts:button>

        </div>
    </form>


    {{-- modal add barang --}}
    <x-filament::modal id="modal-new-barang" :close-by-clicking-away="false" :autofocus="false">
        <x-slot:heading>Tambah Barang</x-slot:heading>

        <livewire:Master.Barang.Add :term="$createTerm" :key="Str::random()" @new-barang-created="$refresh" />
    </x-filament::modal>


    {{-- modal add supplier --}}
    <x-filament::modal id="modal-new-supplier" :close-by-clicking-away="false" :autofocus="false">
        <x-slot:heading>Tambah Supplier</x-slot:heading>


        <livewire:Master.Supplier.Add :term="$createTerm" :key="Str::random()" @new-supplier-created="$refresh" />
    </x-filament::modal>
</div>

@script
    <script>
        Alpine.data('listPembelian', () => {
            return {
                sku: '',
                searchItem: '',
                totalItem: 0,
                totalBeli: 0, //sum cartItems.subTotal
                cartItems: $wire.entangle('cartItems'),


                addingCart(id) {
                    try {
                        $wire.getBarang(id).then(barang => {
                            const existingItem = this.cartItems.find(item => item.id === barang.id);

                            if (existingItem) {
                                existingItem.jumlah += 1;
                            } else {
                                const newItem = {
                                    id: barang.id,
                                    sku: barang.sku,
                                    nama: barang.nama,
                                    satuan: barang.satuan,
                                    jumlah: 1,
                                    harga: 0,
                                    batch: '',
                                    subTotal: 0
                                };
                                this.cartItems.push(newItem);
                                this.totalItem = this.cartItems.length;
                                this.sku = '';
                                this.searchItem = '';
                            }

                        }).catch(error => {
                            console.error('Error retrieving barang data:', error);
                        });
                    } catch (error) {
                        console.error('Error in addingCart:', error);
                    }

                },

                recalculateTotal(index) {
                    const item = this.cartItems[index];
                    item.subTotal = item.jumlah * item.harga;

                    this.totalBeli = this.cartItems.reduce((total, item) => total + item.subTotal, 0);
                },

                removeItemFromCart(index) {
                    this.cartItems.splice(index, 1);

                    this.totalItem = this.cartItems.length;
                    this.totalBeli = this.cartItems.reduce((total, item) => total + item.subTotal, 0);
                },
            }
        })
    </script>
@endscript
