<div x-data="distribusi" class="flex flex-col gap-2">

    {{-- tab action --}}
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
            this.$nextTick(() => {
                this.$refs.searchInput.focus();
            })
        },
    }">
        <div class="flex flex-row bg-white rounded-lg py-2 px-4">

            {{-- search input --}}
            <div class="flex flex-row w-full gap-2 items-center">

                <div class="relative w-3/4 lg:w-1/3">
                    <!-- Input Field -->
                    <input x-ref="searchInput" wire:model.live.debounce.300ms='search' placeholder="Cari No. Transaksi Distribusi"
                        class="h-8 px-10 transition-all duration-300 border-gray-200 rounded-lg w-full focus:outline-none" autocomplete="off" />

                    <!-- Icon (Search) -->
                    <x-ts:icon name="tabler.scan" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />

                    {{-- clear icon --}}
                    <button x-show="searchTerm" @click="reset" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-red-500 hover:text-red-600" type="button">
                        <x-ts:icon name="tabler.x" class="w-4 h-4" />
                    </button>

                </div>
            </div>

            <div class="flex ml-auto justify-end gap-2 items-center">

                <span role="button" x-show="transaksiPanel" x-on:click="transaksiDistribusi()" class="flex flex-row items-center px-2 py-1 text-red-500 hover:bg-red-200/25 hover:rounded-lg ">
                    <x-ts:icon name="tabler.chevron-left" class="w-5 h-5" />
                    Kembali
                </span>

                <x-ts:button sm icon="tabler.plus" x-show="!transaksiPanel" x-on:click="transaksiDistribusi()">
                    Distribusi
                </x-ts:button>
            </div>
        </div>


        {{-- Hasil Cari Untuk Penerimaan Barang --}}
        <div x-show="searchTerm" @keyup.escape.window="searchTerm = ''" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95" class="relative w-3/5">

            <div class="absolute z-10 inset-0 left-0">
                <div class="relative bg-white rounded-md shadow-2xl p-4 mt-1 border-2 border-b-4 border-indigo-500 transition-transform transform">
                    <div class="absolute top-[-12px] left-[3%] transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-b-8 border-transparent border-b-indigo-500 mb-1">
                    </div>
                    {{-- content --}}

                    <div class="mb-2">
                        <span class="text-gray-500 italic" wire:loading wire:target='search'> Searching : </span>
                        <span class="text-gray-500 italic" wire:loading.remove> Hasil Pencarian : </span>
                        <span class="font-semibold text-indigo-500" x-text="searchTerm"></span>
                    </div>

                    <div wire:loading wire:target="search" class="text-sm text-gray-400 italic">
                        Loading ...
                    </div>

                    <div wire:loading.remove>
                        @if ($distribusi)
                            <livewire:Distribusi.Pencarian :$distribusi :key="Str::random()" />
                        @else
                            <span class="text-sm text-danger-500">Data tidak ditemukan. </span>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
    {{-- end tab action --}}


    <div x-show="!transaksiPanel" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" class="flex flex-col gap-2">
        {{-- stats --}}
        <div class="w-full">
            <livewire:Distribusi.Stats />
        </div>
        {{-- end stats --}}

        {{-- table --}}
        <div class="w-full">
            <livewire:Distribusi.TableDistribusi :key="Str::random()" />
        </div>

        {{-- end table --}}
    </div>

    <div x-show="transaksiPanel" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95">
        <livewire:Distribusi.Transaksi :key="Str::random()" />
    </div>

</div>

@script
    <script>
        Alpine.data('distribusi', () => {
            return {
                transaksiPanel: false,

                transaksiDistribusi() {
                    this.transaksiPanel = !this.transaksiPanel;
                }
            }
        });
    </script>
@endscript
