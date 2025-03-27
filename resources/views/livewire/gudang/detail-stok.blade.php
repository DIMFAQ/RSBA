<div>
    <div class="flex flex-col gap-4">

        <div class="flex flex-row gap-2 border border-gray-200 border-s-4 border-s-indigo-500 px-4 py-2">
            <div class="flex items-center">
                Stok tersedia saat ini :
                <span class="text-indigo-500 font-semibold">
                    &nbsp; {{ $stoks->sum('stok') }}
                </span>
            </div>
        </div>

        <table class="min-w-full table-fixed border-collapses overflow-y-auto overflow-x-auto">
            <thead>
                <tr class="text-left text-sm text-gray-600 border-b">
                    <th class="py-2 px-4">No.</th>
                    <th class="py-2 px-4">Stok Id</th>
                    <th class="py-2 px-4">Tgl Masuk</th>
                    <th class="py-2 px-4">Jumlah Masuk</th>
                    <th class="py-2 px-4">Distribusikan</th>
                    <th class="py-2 px-4">Terakhir Distribusi</th>
                    <th class="py-2 px-4">Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stoks as $index => $item)
                    <tr class="text-left text-sm text-gray-600 border-b even:bg-gray-200/25" :key="{{ $index }}">
                        <td class="py-2 px-4">{{ ($stoks->currentPage() - 1) * $stoks->perPage() + $loop->iteration }}</td>
                        <td class="py-2 px-4">{{ $item->id }}</td>
                        <td class="py-2 px-4">{{ $item->penerimaanDet->penerimaan->tanggal }}</td>
                        <td class="py-2 px-4">{{ $item->penerimaanDet->jumlah }}</td>
                        <td class="py-2 px-4">
                            <span role="button" class="flex flex-row gap-2 items-center" wire:model.live='stokId'
                                x-on:click="$wire.set('stokId',{{ $item->id }});$dispatch('open-modal', {id:'modal-distribusi-per-stok'})">
                                {{ $item->distribusiDetails->sum('jml') }}
                                <x-ts:icon name="tabler.external-link" class="w-3 h-3" />
                            </span>
                        </td>
                        <td class="py-2 px-4">
                            @php
                                $terakhirKeluar = '-';
                                if ($item->distribusiDetails->last()?->created_at) {
                                    $terakhirKeluar = \Carbon\Carbon::parse($item->distribusiDetails->last()?->created_at)->diffForHumans();
                                }
                            @endphp

                            {{ $terakhirKeluar }}
                        </td>
                        <td class="py-2 px-4 @if ($item->stok == 0) text-red-500 italic @endif">
                            {{ $item->stok == 0 ? 'Habis' : $item->stok }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-2 px-4 text-center italic text-gray-400">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        <div class="px-4 py-3 text-sm">
            {{ $stoks->links() }}
        </div>

    </div>


    <x-filament::modal id="modal-distribusi-per-stok" width="3xl">
        <x-slot name="heading">
            Distribusi Stok
        </x-slot>

        <div wire:loading wire:target='stokId'>
            Wait...
        </div>
        <div wire:loading.remove wire:target='stokId'>
            <livewire:Gudang.ViewStokTerdistribusi :$stokId :key="Str::random()" />
        </div>

    </x-filament::modal>
</div>
