<div class="flex flex-col gap-3">
    <div class="border rounded-md border-gray-200 p-2 grid grid-cols-2 gap-3">
        <div class="flex flex-col text-xl text-indigo-500">
            <span class="text-sm italic text-gray-400">Kode Asset :</span>
            {{ $assetBarang->kode }}
        </div>
        <div class="flex flex-col gap-1">
            <span class="text-lg text-indigo-500 font-bold">{{ $assetBarang->barang->nama }}</span>
            <div class="flex flex-row gap-3 text-sm text-gray-400 font-light">
                <span>{{ $assetBarang->barang->kategori->nama }}, </span> Di : {{ $assetBarang->ruangan->nama }}
            </div>
        </div>
    </div>

    <ol class="relative border-s border-gray-200 dark:border-gray-700">
        @foreach ($logs as $item)
            <li class="mb-10 ms-4">
                <div
                    class="absolute w-3 h-3 {{ $loop->first ? 'bg-primary-500' : 'bg-gray-200' }} rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700">
                </div>
                <time
                    class="mb-1 font-semibold leading-none {{ $loop->first ? 'text-primary-500' : 'text-gray-400' }} dark:text-gray-500">
                    {{ $item->created_at }}
                </time>

                <h3 class="text-sm text-gray-500 dark:text-white italic">User : {{ $item->user->karyawan->nama }}</h3>
                <p class="text-base font-normal text-gray-500 dark:text-gray-400">
                    {{ $item->keterangan }}
                </p>
            </li>
        @endforeach
    </ol>


</div>
