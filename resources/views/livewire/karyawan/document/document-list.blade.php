<div class="w-full">
    <div class="flex flex-col gap-2">
        <div class="ml-auto flex justify-end">
            <x-ts:button xs icon="tabler.plus" x-on:click="$dispatch('open-modal',{id:'add-document-karyawan'})">Upload</x-ts:button>
        </div>
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">

                <div class="overflow-hidden">
                    <table class="text-surface min-w-full text-left text-sm dark:text-white">
                        <thead class="border-b border-neutral-200 font-semibold dark:border-white/10">
                            <tr>
                                <th scope="col" class="px-6 py-2">Nama Document</th>
                                <th scope="col" class="px-6 py-2">Jenis</th>
                                <th scope="col" class="px-6 py-2">Tgl Upload</th>
                                <th scope="col" class="px-6 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents as $item)
                                <tr class="border-b border-neutral-200 transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-white/10 dark:hover:bg-neutral-600">
                                    <td class="whitespace-nowrap px-6 py-2">{{ $item->nama }}</td>
                                    <td class="whitespace-nowrap px-6 py-2">{{ $item->jenis }}</td>
                                    <td class="whitespace-nowrap px-6 py-2">{{ $item->created_at }}</td>
                                    <td class="flex flex-row gap-2 whitespace-nowrap px-6 py-2">
                                        <x-ts:button sm icon="tabler.folder-open" wire:click='view({{ $item->id }})' loading="view({{ $item->id }})" />

                                        {{-- delete --}}
                                        <x-ts:button sm icon="tabler.trash" color="red" wire:click='delete({{ $item->id }})' loading="delete({{ $item->id }})" />
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-b border-neutral-200 transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-white/10 dark:hover:bg-neutral-600">
                                    <td colspan="5" class="bg-red-100/75 text-center italic">Tidak ada documents</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <x-filament::modal id="add-document-karyawan" width="2xl" :close-by-clicking-away="false" :autofocus="false">
        <x-slot name="heading">
            Tambah Dokumen
        </x-slot>
        <livewire:Karyawan.Document.Add :id="$karyawanId" :key="Str::random()" @document-karyawan-created="$refresh" />
    </x-filament::modal>


    <x-filament::modal id="view-document-karyawan" width="6xl" class="h-screen min-h-full" :close-by-clicking-away="false" :autofocus="false">
        <x-slot name="heading">
            Document <span class="text-primary-500 font-semibold">{{ $documentsSelected?->nama }}</span>
        </x-slot>

        <livewire:Karyawan.Document.View :$documentsSelected :key="Str::random()" />
    </x-filament::modal>
</div>
