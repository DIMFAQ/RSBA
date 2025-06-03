<div>
    <div class="flex-1 overflow-auto p-2">
        <div class="flex max-h-screen flex-col gap-2 overflow-auto">
            <table class="min-w-full table-fixed border-collapse">
                <thead class="sticky top-0 bg-white">
                    <tr class="border-b text-left text-sm text-gray-600">
                        <th class="px-4 py-2">No. Rekemdis</th>
                        <th class="px-4 py-2">Nama Pasien</th>
                        <th class="px-4 py-2">Tgl Checkout</th>
                        <th class="px-4 py-2">Sep</th>
                        <th class="px-4 py-2">DPJP </th>
                        <th class="px-4 py-2">Sudah Klaim ?</th>
                        <th class="px-4 py-2">Jumlah Dokter</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="overflow-auto">

                    @foreach ($this->getRows() as $item)
                        <tr class="border-b bg-white text-left text-sm even:bg-gray-100" wire:key="{{ $item->id }}">
                            <td class="px-4 py-2">{{ $item->no_rekmedis }}</td>
                            <td class="px-4 py-2">{{ $item->nama_pasien }}</td>
                            <td class="px-4 py-2">{{ $item->tgl_checkout }}</td>
                            <td class="px-4 py-2">{{ $item->sep }}</td>
                            <td class="px-4 py-2">{{ $item->dpjp }}</td>
                            <td class="px-4 py-2">
                                <x-ts:icon :name="$item->disetujui ? 'tabler.checks' : 'tabler.square-rounded-minus'" :class="$item->disetujui ? 'text-green-500' : 'text-red-500'"></x-ts:icon>
                            </td>
                            <td class="px-4 py-2">
                                {{ $item->dokter->count() }}
                            </td>
                            <td class="px-4 py-2">
                                <x-ts:button wire:click="editDokter({{ $item->id }})" icon="tabler.edit" sm outline>
                                    Edit
                                </x-ts:button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
        <div class="flex w-full justify-end px-4 py-3 text-sm">
            {{ $this->getRows->links() }}
        </div>


        {{-- modal Edit Dokter --}}
        <x-filament::modal id="modal-edit-dokter" width="4xl" class="max-h-screen overflow-auto">
            <x-slot:heading>Dokter</x-slot:heading>
            <livewire:Jasmed.Dokter.Edit :id="$selectedId" :key="'edit-dokter-' . $selectedId" />
        </x-filament::modal>
    </div>
