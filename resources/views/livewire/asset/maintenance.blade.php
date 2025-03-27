<div class="flex flex-col gap-3">
    <div class="grid grid-cols-2 gap-3 rounded-md border border-gray-200 p-2">
        <div class="flex flex-col text-xl text-indigo-500">
            <span class="text-sm italic text-gray-400">Kode Asset :</span>
            {{ $assetBarang->kode }}
        </div>
        <div class="flex flex-col gap-1">
            <span class="text-lg font-bold text-indigo-500">{{ $assetBarang->barang->nama }}</span>
            <div class="flex flex-row gap-3 text-sm font-light text-gray-400">
                <span>{{ $assetBarang->barang->kategori->nama }}, </span> Di : {{ $assetBarang->ruangan->nama }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 rounded-md border border-gray-200 p-2">

    </div>

</div>
