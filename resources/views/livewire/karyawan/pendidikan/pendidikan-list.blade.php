<div class="flex flex-col gap-2">
    <div class="ml-auto flex justify-end">
        <x-ts:button xs x-on:click="$dispatch('open-modal',{id:'new-pendidikan'})" icon="tabler.plus">Tambah</x-ts:button>
    </div>

    <div class="w-full">
        <div class="flex flex-col">

            <div class="overflow-x-auto lg:-mx-8">
                <div class="inline-block min-w-full py-2 lg:px-8">

                    <div class="overflow-hidden">
                        <table class="text-surface min-w-full text-left text-sm dark:text-white">
                            <thead class="border-b border-neutral-200 font-semibold dark:border-white/10">
                                <tr>
                                    <th scope="col" class="px-6 py-2">Pendidikan</th>
                                    <th scope="col" class="px-6 py-2">Tahun Lulus</th>
                                    <th scope="col" class="px-6 py-2">Institusi</th>
                                    <th scope="col" class="px-6 py-2">Gelar</th>
                                    <th scope="col" class="px-6 py-2">Tingkat Pendidikan</th>
                                    <th scope="col" class="px-6 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendidikans as $item)
                                    <tr class="border-b border-neutral-200 transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-white/10 dark:hover:bg-neutral-600">
                                        <td class="whitespace-nowrap px-6 py-2">{{ $item->nama }}</td>
                                        <td class="whitespace-nowrap px-6 py-2">{{ $item->tahun_lulus }}</td>
                                        <td class="whitespace-nowrap px-6 py-2">{{ $item->instansi }}</td>
                                        <td class="whitespace-nowrap px-6 py-2">{{ $item->gelar }}</td>
                                        <td class="whitespace-nowrap px-6 py-2">{{ $item->tingkat->nama() }}</td>
                                        <td class="flex flex-row gap-2 whitespace-nowrap px-6 py-2">
                                            <x-ts:button sm icon="tabler.trash" color="red" wire:click='delete({{ $item->id }})' loading="delete({{ $item->id }})" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="border-b border-neutral-200 transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-white/10 dark:hover:bg-neutral-600">
                                        <td colspan="6" class="bg-red-100/75 text-center italic">Tidak ada pendidikan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Modal new-pendidikan --}}
    <x-filament::modal id="new-pendidikan" width="xl" :autofocus="false" :close-by-clicking-away="false">
        <x-slot name="heading">
            Tambah Pendidikan
        </x-slot>
        {{-- Form new-pendidikan --}}
        <livewire:Karyawan.Pendidikan.Add @pendidikan-karyawan-created="$refresh" :$karyawan :key="Str::random()" />
    </x-filament::modal>
</div>
