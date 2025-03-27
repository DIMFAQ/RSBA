<div class="flex flex-col gap-2">
    <div class="grid grid-cols-2 mb-2 border border-gray-200 rounded p-3">
        <div class="flex flex-col">
            <span class="text-xs  font-light font-gray-500">No. Transaksi</span>
            <h1 class="text-gray-500 text-2xl font-bold uppercase">{{ $pembelian?->no ?? 'Tidak Ditemukan' }}</h1>
            <span class="flex flex-row text-[0.45rem] gap-2 mt-2 ">
                @php
                    $status = $pembelian->status;
                    $statusBeli = fn(string $status): string => match ($status) {
                        'waiting' => 'amber',
                        'selesai' => 'green',
                        'dibatalkan' => 'red',
                        default => 'secondary',
                    };
                    $badgeStatus = $statusBeli($status);
                @endphp
                <x-ts:badge :text="ucwords($pembelian->status)" :color="$badgeStatus" outline xs />


                {{-- pembayaran --}}
                @php
                    $pembayaran = $pembelian?->status_pembayaran ?? null;
                    $statusPembayaran = fn($pembayaran) => match ($pembayaran) {
                        'lunas' => 'green',
                        'tempo' => 'amber',
                        null => 'red',
                    };
                    $badgePembayaran = $statusPembayaran($pembayaran);
                @endphp
                <x-ts:badge :text="$pembelian?->status_pembayaran ? ucfirst($pembelian->status_pembayaran) : 'Belum Dibayar'" :color="$badgePembayaran" outline xs />
            </span>
        </div>
        <div class="flex flex-col">
            <div class="flex items-center">
                <span class="w-[150px]">Supplier</span> : {{ $pembelian?->supplier->nama }}
            </div>
            <div class="flex items-center">
                <span class="w-[150px]">Tanggal Beli</span> :
                {{ Carbon\Carbon::parse($pembelian?->tgl)->translatedFormat('d M Y') }}
            </div>
            <div class="flex items-center">
                <span class="w-[150px]">Jenis</span> :
                {{ $pembelian->jenis }}
            </div>
        </div>
    </div>

    <div class="w-full">

        {{-- table --}}
        <table class="min-w-full table-fixed border-collapses">
            <thead class="bg-gray-300">
                <tr class="text-left text-sm text-gray-500 uppercase font-semibold">
                    <th class="py-2 px-4"></th>
                    <th class="py-2 px-4">Barang</th>
                    <th class="py-2 px-4">Satuan</th>
                    <th class="py-2 px-4">Jumlah Pesan</th>
                    <th class="py-2 px-4">Diterima</th>
                    <th class="py-2 px-4">Tgl Diterima</th>
                    <th class="py-2 px-4">Harga Satuan</th>
                    <th class="py-2 px-4">Oleh</th>
                </tr>
            </thead>

            <div class="overflow-y-auto">
                <tbody>
                    @foreach ($pembelian->pembelians as $itemBeli)
                        @php
                            $rowClass = 'bg-gray-200/25';
                            $iconStatus = 'checks';
                            $iconColor = 'green';
                            if ((int) $itemBeli->terimas->sum('jumlah') === 0) {
                                $rowClass = 'bg-red-200/25';
                                $iconStatus = 'hourglass-high'; //circle-dashed
                                $iconColor = 'red';
                            } elseif ((int) $itemBeli->jumlah > (int) $itemBeli->terimas->sum('jumlah')) {
                                $rowClass = $rowClass = 'bg-orange-200/25';
                                $iconStatus = 'progress-check';
                                $iconColor = 'orange';
                            }
                        @endphp
                        <tr class="text-sm font-semibold {{ $rowClass }}">
                            <td class="py-2 px-4">
                                <x-ts:icon :name="'tabler.' . $iconStatus" :color="$iconColor" />
                            </td>
                            <td class="py-2 px-4">{{ $itemBeli->barang->nama }}</td>
                            <td class="py-2 px-4">{{ $itemBeli->barang->satuan->nama }}</td>
                            <td class="py-2 px-4">{{ $itemBeli->jumlah }}</td>
                            <td colspan="4" class="py-2 px-4">{{ $itemBeli->terimas->sum('jumlah') }}</td>
                        </tr>
                        @forelse ($itemBeli->terimas as $terima)
                            <tr class="text-sm @if ($loop->last) border-b-2 border-b-gray-400 @endif">
                                <td colspan="4" class="py-1 px-4"></td>
                                <td class="py-1 px-4">{{ $terima->jumlah }}</td>
                                <td class="py-1 px-4">{{ $terima->created_at->format('d/m/Y') }}</td>
                                <td class="py-1 px-4">{{ formatRupiah($terima->stoks->harga_satuan) }}</td>
                                <td class="py-1 px-4">{{ $terima->penerimaan->user->karyawan->nama }}</td>
                            </tr>
                        @empty
                            <tr class="text-sm border-b-2 border-b-gray-400 ">
                                <td colspan="4" class="py-1 px-4"></td>
                                <td colspan="4" class="text-center justify-center">
                                    <span class="font-thin italic">Barang belum diterima.</span>
                                </td>
                            </tr>
                        @endforelse
                    @endforeach

                </tbody>
            </div>
        </table>
        {{-- end table --}}

    </div>

    <div class="flex justify-end mt-4">
        <x-ts:button sm color="red" x-on:click="$dispatch('close-modal',{id:'modal-detail-pembelian'}), $dispatch('close-cari-pembelian',{value:''})">Tutup</x-ts:button>
    </div>

</div>
