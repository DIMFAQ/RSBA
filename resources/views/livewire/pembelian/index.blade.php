<div x-data="pembelian" class="flex flex-col gap-2">

    <div class="flex flex-row gap-2 rounded-lg bg-white px-4 py-2">
        @can('terima-pembelian')
            {{-- search input --}}
            <div class="flex w-full flex-col gap-2 lg:w-1/2">
                <livewire:Pembelian.Cari />
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



    {{-- Table List Pembelian --}}
    <div x-show="panelActive === 'main'" class="w-full">
        <div class="w-full rounded-md border-2 border-white p-1" x-data="{ showStats: false, refreshKey: Date.now() }">
            <x-ts:toggle sm @click='showStats = !showStats; refreshKey = Date.now()' label="Stats" />

            <div x-show="showStats">
                <livewire:pembelian.stats x-bind:key="'stats'" />
                {{-- x-bind:key="'stats-' + refreshKey" --}}
            </div>
        </div>


        <x-ts:tab :selected="$this->getRequestPembelianProperty ? 'Permintaan' : 'Transaksi'" x-on:navigate="$wire.set('tab',$event.detail.select)">

            @if ($this->getRequestPembelianProperty)
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
                <livewire:Pembelian.Pesanan.Add @new-pesanan-created="$refresh" :key="'pesanan-' . Str::random()" />
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
