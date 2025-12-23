<div class="flex flex-col gap-3">
    <livewire:Asset.Title :assetBarang="$assetBarang" :key="'title' . $assetBarang->id" />

    <div class="grid grid-cols-1 gap-3 lg:grid-cols-3">
        <div class="rounded-md border border-gray-200 p-2">
            <span class="text-sm italic text-indigo-400">Detail Asset</span>
            <div class="mt-2 flex flex-col gap-1 border-t border-gray-200 pt-2">
                <span class="font-semibold">{{ $assetBarang->barang->nama }}</span>
                <span class="text-sm text-gray-500">Kode : {{ $assetBarang->kode }}</span>
                <span class="text-sm text-gray-500">Kategori : {{ $assetBarang->barang->kategori->nama ?? '-' }}</span>
                <span class="text-sm text-gray-500">Lokasi : {{ $assetBarang->ruangan->nama ?? '-' }}</span>
                <span class="text-sm text-gray-500">Nilai Saat Diterima : {{ $assetBarang->nilai ?? '-' }}</span>
                <span class="text-sm text-gray-500">Status : {{ Str::ucfirst($assetBarang->status) }}</span>
                <span class="text-sm text-gray-500">Diterima Pada : {{ Carbon\Carbon::parse($assetBarang->tanggal_catat)->locale('ID')->translatedFormat('d M Y') }}</span>
            </div>
        </div>
        <div class="rounded-md border border-gray-200 p-2">
            <span class="text-sm italic text-indigo-400">Spesifikasi Assets</span>
            <div class="mt-2 flex flex-col gap-1 border-t border-gray-200 pt-2">
                @foreach ($assetBarang->specs as $spec)
                    <span class="text-sm text-gray-500">{{ $spec->label }} : {{ $spec->value }}</span>
                @endforeach
                @if ($assetBarang->specs->isEmpty())
                    <span class="text-sm text-gray-500">Tidak ada spesifikasi.</span>
                @endif
            </div>


        </div>

        <div class="rounded-md border border-gray-200 p-2">
            <span class="text-sm italic text-indigo-400">Komponen-komponen</span>
            <div class="mt-2 flex flex-col gap-2 border-t border-gray-200 pt-2">
                @if ($assetBarang->components->isNotEmpty())
                    @foreach ($assetBarang->components as $komponen)
                        <span class="text-sm font-semibold text-gray-500">{{ $komponen->barang->nama }}</span>
                        <div class="ms-2 flex flex-col gap-1 text-xs">
                            <span class="text-gray-400">Kode: {{ $komponen->kode }}</span>
                            <span class="text-gray-400">Status: {{ $komponen->status }}</span>
                            <span class="text-gray-400">Nilai: {{ $komponen->nilai }}</span>

                            <span class="text-gray-400">Spesifikasi :</span>
                            <span class="ms-3 flex flex-col gap-1 text-xs">
                                @foreach ($komponen->specs as $spec)
                                    <span class="text-gray-400">{{ $spec->label }} : {{ $spec->value }}</span>
                                @endforeach
                            </span>

                        </div>
                    @endforeach
                @else
                    <span class="text-sm text-gray-400">Tidak memiliki komponen.</span>
                @endif

            </div>
        </div>

    </div>
