<div class="flex flex-col gap-3">

    {{-- header --}}
    <div class="mb-2 grid grid-cols-2 rounded border border-gray-200 p-3">
        <div class="flex flex-col">
            <span class="font-gray-500 text-xs font-light">No. Transaksi</span>
            <h1 class="text-4xl font-bold uppercase text-gray-500">{{ $pembelian?->no ?? 'Tidak Ditemukan' }}
            </h1>
            <span class="mt-2 flex flex-row gap-2 text-[0.45rem]">
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


    {{-- form barng diterim --}}
    <form wire:submit.prevent='submit'>
        <div class="flex flex-col gap-2">
            <span class="font-semibold text-indigo-500">Barang Yang Diterima </span>
            <div class="grid grid-cols-4 gap-2">
                <x-ts:date wire:model.lazy='tgl_diterima' placeholder="Tgl Diterima" />

                <x-ts:input wire:model.lazy='no_invoice' placeholder="No. Invoice / Faktur / No. Nota" />

                <x-ts:input wire:model.lazy='keterangan' placeholder="Keterangan" />

            </div>

            {{-- init data menggunakan alpine js --}}
            <div x-data="{
                subtotals: @entangle('terimaBarang'),
                totalHarga: @entangle('totalHargaTerimaBarang'),
                calculateSubtotal(index) {
                    const item = this.subtotals[index];
                    item.subtotal = (item.jumlahDiterima || 0) * (item.hargaSatuan || 0);
                    this.updateTotalHarga();
                },
            
                updateTotalHarga() {
                    this.totalHarga = this.subtotals.reduce((sum, item) => sum + (item.subtotal || 0), 0);
                },
            
                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(value);
                },
            }" class="w-full overflow-auto">
                {{-- end init data --}}

                <table class="border-collapses min-w-full table-fixed">
                    <thead>
                        <tr class="border-b text-left text-sm text-gray-600">
                            <th class="px-4 py-2">No.</th>
                            <th class="px-4 py-2">Barang</th>
                            <th class="px-4 py-2">Satuan</th>
                            <th class="px-4 py-2">Jumlah Dipesan</th>
                            <th class="px-4 py-2">Jumlah Telah Diterima</th>
                            <th class="px-4 py-2">Jumlah Diterima</th>
                            <th class="px-4 py-2">Harga Satuan</th>
                            <th class="px-4 py-2">Batch</th>
                            <th class="px-4 py-2">Sub Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($detailPesanan as $index => $item)
                            <tr class="border-b even:bg-gray-100/75 hover:bg-indigo-100" :key="{{ $index }}">
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2">{{ $item->barang->nama }}</td>
                                <td class="px-4 py-2">{{ $item->barang->satuan->nama }}</td>
                                <td class="px-4 py-2">{{ $item->jumlah }}</td>
                                <td class="px-4 py-2">{{ $item->terimas?->sum('jumlah') }}</td>

                                @php
                                    $sisaBlmDiterima = $item->jumlah - $item->terimas?->sum('jumlah');
                                @endphp
                                @if ($sisaBlmDiterima > 0)
                                    {{-- Diterima --}}
                                    <td class="px-4 py-2">
                                        <input required type="number" x-model.number="subtotals[{{ $index }}].jumlahDiterima" x-on:input="calculateSubtotal({{ $index }})"
                                            max="{{ $sisaBlmDiterima }}" class="h-8 max-w-24 rounded-lg border border-gray-100" placeholder="Diterima" />

                                        @error('any')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Harga Satuan --}}
                                    <td class="px-4 py-2">
                                        <input required type="number" x-model.number="subtotals[{{ $index }}].hargaSatuan" x-on:input="calculateSubtotal({{ $index }})"
                                            class="h-8 max-w-32 rounded-lg border border-gray-100" placeholder="Harga Satuan" />

                                        @error('any')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- Batch --}}
                                    <td class="px-4 py-2">
                                        <input type="text" wire:change.debounce.300ms="" class="h-8 max-w-24 rounded-lg border border-gray-100" placeholder="Batch" />

                                        @error('any')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    {{-- sub total --}}
                                    <td class="px-4 py-2">
                                        <span x-text="formatCurrency(subtotals[{{ $index }}].subtotal || 0)"></span>
                                    </td>
                                @else
                                    <td colspan="4" class="px-4 py-2">
                                        <x-ts:badge color="teal">Sudah diterima</x-ts:badge>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                                    Tidak ada pesanan barang.
                                </td>
                            </tr>
                        @endforelse



                    </tbody>
                    <tfoot>
                        <tr class="border-b text-center text-lg uppercase text-gray-600">
                            <td colspan="8" class="px-4 py-2">
                                Total
                            </td>
                            <td class="px-4 py-2 font-bold">
                                <span x-text="formatCurrency(totalHarga)"></span>
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </div>
            <div class="ml-auto flex justify-end gap-2">
                <x-ts:button type="button" sm color="red" x-on:click="$dispatch('close-cari-pembelian',{value:''})" loading="$parent.set('seacrh',null)">Batal</x-ts:button>

                {{-- simpan action --}}
                <div class="relative">
                    <div x-data="{
                        waitOrDone: false,
                    }" class="relative">

                        <x-ts:button sm icon="tabler.checks" x-on:click="waitOrDone = true">
                            Simpan
                        </x-ts:button>

                        <!-- Tooltip Modal -->
                        <div class="absolute right-0 z-50 mt-2 max-w-fit rounded-lg bg-white p-4 shadow-lg" x-show="waitOrDone" x-transition x-trap.noscroll="waitOrDone"
                            x-on:click.away="waitOrDone = false" x-on:keydown.escape.window="waitOrDone = false">

                            <!-- Tooltip Header -->
                            <div class="mb-3 flex items-center justify-between">
                                <span class="flex flex-row items-center gap-2 whitespace-nowrap font-medium text-indigo-500">
                                    <x-ts:icon name="tabler.shopping-cart-plus" class="h-5 w-5" />
                                    Konfirmasi Penerimaan Barang
                                </span>
                                <span role="button" x-on:click="waitOrDone = false" class="text-gray-400 hover:text-gray-600">
                                    &times;
                                </span>
                            </div>

                            <!-- Options -->
                            <div class="flex items-center justify-between gap-4">
                                <span class="flex flex-row items-center gap-2 whitespace-nowrap text-sm font-light text-gray-500">
                                    Masih menunggu pengiriman selanjutnya, atau selesaikan transaksi sekarang ?
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 flex justify-end gap-2">
                                <x-ts:button outline sm icon="tabler.file-isr" wire:click="submit('sebagian')" loading="submit('sebagian')">
                                    Simpan, Tunggu Berikutnya
                                </x-ts:button>
                                <x-ts:button outline sm color="green" icon="tabler.checks" wire:click="submit('selesai')" loading="submit('selesai')">
                                    Simpan, Selesaikan Sekarang
                                </x-ts:button>
                            </div>
                        </div>
                        {{-- end Tooltip Modal --}}

                    </div>
                </div>
                {{-- end simpan action --}}

            </div>
        </div>

    </form>

</div>
