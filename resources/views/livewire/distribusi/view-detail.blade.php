@php
    $tglDistribusi = Carbon\Carbon::parse($distribusi->tanggal)->translatedFormat('d M Y');

    $keluarAs = $distribusi->dist_as;
    $colorKeluarAs = fn($keluarAs) => match ($keluarAs) {
        'keluar' => 'red',
        'asset' => 'green',
        null => 'base',
    };
    $colorKeluarAs = $colorKeluarAs($keluarAs);

    // label
    $labelKeluar = fn($keluarAs) => match ($keluarAs) {
        'keluar' => 'Pengeluaran',
        'asset' => 'Sebagai Aset',
        null => '-',
    };
    $labelKeluar = $labelKeluar($keluarAs);
@endphp

<div class="flex flex-col gap-2">
    <div class="grid grid-cols-2 mb-2 border border-gray-200 rounded p-3">
        <div class="flex flex-col">
            {{-- <span class="text-xs font-light font-gray-500">ID Transaksi</span> --}}
            <h1 class="text-gray-500 text-2xl font-bold uppercase">{{ $distribusi->id }}</h1>

            {{-- footer --}}
            <span class="flex flex-row text-[0.45rem] gap-2 mt-2 items-center ">
                <span>{{ $tglDistribusi }}</span>
                <x-ts:badge :text="$labelKeluar" :color="$colorKeluarAs" outline xs />
            </span>
        </div>
        <div class="flex flex-col ">
            <div class="flex items-center">
                <span class="w-[150px]">Ke</span> : {{ $distribusi->ruangan->nama }}
            </div>
            <div class="flex items-center">
                <span class="w-[150px]">Diterima Oleh</span> : {{ $distribusi->pengirim_nama }}
            </div>

        </div>
    </div>

    <div>
        <x-table-static :$headers :$rows striped :$paginator />
    </div>

</div>
