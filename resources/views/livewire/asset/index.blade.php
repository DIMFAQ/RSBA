<div class="w-full flex flex-col gap-2">

    <div class="bg-white rounded-lg flex flex-row justify-between p-4">
        <div class="relative w-3/4 lg:w-1/3">
            <input id="search-asset" placeholder="Cari Barang..." type="text" class="h-8 px-10 transition-all duration-300 border-gray-200 rounded-lg w-full focus:outline-none" />

            <!-- Icon (Search) -->
            <x-ts:icon name="tabler.scan" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
        </div>

    </div>

    <div class="w-full bg-white rounded-lg p-4">
        <livewire:Asset.TableAsset :key="Str::random()" />
    </div>

</div>
