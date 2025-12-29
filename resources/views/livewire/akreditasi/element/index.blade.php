<div class="flex flex-col gap-2" x-data="{ selectedBabNama: null }">

    <div class="flex flex-row justify-between rounded-lg bg-white px-4 py-2">

        {{-- search input --}}
        <div class="relative flex w-3/4 flex-row items-center gap-2 lg:w-1/3">

            <div class="w-full">
                <!-- Input Field -->
                <input x-ref="searchInput" placeholder="Pencarian..." class="h-8 w-full rounded-lg border-gray-200 px-10 transition-all duration-300 focus:outline-none" autocomplete="off" />

                <!-- Icon (Search) -->
                <x-ts:icon name="tabler.search" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 transform text-gray-400" />

                {{-- clear icon --}}
                {{-- <button x-show="searchTerm" @click="reset" class="absolute right-3 top-1/2 -translate-y-1/2 transform text-red-500 hover:text-red-600" type="button">
                    <x-ts:icon name="tabler.x" class="h-4 w-4" />
                </button> --}}

            </div>
        </div>

        <div class="flex flex-row gap-2">

            <x-ts:button sm outline icon="tabler.plus" x-on:click="$dispatch('open-modal',{id:'modal-new-bab'})">Bab</x-ts:button>

            <x-ts:button sm outline icon="tabler.plus" x-on:click="$dispatch('open-modal',{id:'modal-new-penilaian'})">Element Penilaian</x-ts:button>

            <x-ts:button sm outline icon="tabler.file-type-zip" loading="downloadZip()" wire:click="downloadZip()">Download</x-ts:button>



            <x-filament::modal id="modal-new-bab" width="2xl">
                <x-slot:heading>Bab Standar</x-slot:heading>
                <livewire:Akreditasi.Element.AddBab :chapterId="$chapter->id" />
            </x-filament::modal>

            <x-filament::modal id="modal-new-penilaian" width="4xl">
                <x-slot:heading>Element Penilaian Bab</x-slot:heading>
                <livewire:Akreditasi.Ep.AddElementPenilaian />
            </x-filament::modal>

        </div>
    </div>


    {{-- Bab Standar --}}
    <div class="flex flex-col gap-6 rounded-md bg-white p-4">
        @foreach ($this->babs as $item)
            @switch($item->bab)
                @case('bab')
                    {{-- Header Bab --}}
                    <div class="flex flex-col gap-2">
                        <span class="font-semibold">{{ $item->no }}. {{ $item->nama }}</span>
                    </div>
                @break

                @case('sub')
                    {{-- Sub Bab --}}
                    <div class="ms-4 flex flex-row gap-2">
                        {{-- Status & Nilai Container --}}
                        <div class="flex w-32 flex-col gap-2">
                            @php
                                $colorBerkas = $item->elements_with_files_count === $item->elements_count ? 'green' : 'red';

                                $totalTarget = $item->elements->sum('target_nilai');
                                $totalNilai = $item->elements->sum('nilai');
                                $persentase = $totalTarget > 0 ? ($totalNilai / $totalTarget) * 100 : 0;

                                if ($persentase < 50) {
                                    $colorNilai = 'red'; // Merah - Rendah
                                } elseif ($persentase >= 50 && $persentase < 100) {
                                    $colorNilai = 'yellow';
                                } else {
                                    $colorNilai = 'green';
                                }
                            @endphp

                            {{-- Status Element By Chapter --}}
                            <div x-on:click="
                            $wire.set('babIdSelected',{{ $item->id }}); 
                            $dispatch('open-modal',{id:'modal-upload-berkas'}); 
                            selectedBabNama= `{{ $item->nama }}`"
                                class="border-{{ $colorBerkas }}-300 bg-{{ $colorBerkas }}-50 hover:border-{{ $colorBerkas }}-400 hover:bg-{{ $colorBerkas }}-300 flex flex-1 cursor-pointer flex-col rounded-md border p-2 shadow-md hover:shadow-2xl">

                                <div class="flex flex-1 items-center justify-center">
                                    <span class="text-{{ $colorBerkas }}-500 text-xl font-medium uppercase">{{ $item->elements_with_files_count }} / {{ $item->elements_count }}</span>
                                </div>
                                <div class="text-{{ $colorBerkas }}-400 flex flex-col text-left text-xs italic">
                                    <span>Berkas</span>
                                    <span>Element : </span>
                                    <span>Terupload : </span>
                                </div>
                                <div class="mt-2 text-center">
                                    <button class="text-xs italic text-gray-300">
                                        Detail
                                    </button>
                                </div>
                            </div>

                            {{-- Validasi Assesor --}}
                            <div class="border-{{ $colorNilai }}-300 bg-{{ $colorNilai }}-50 flex flex-1 flex-col rounded-md border p-2 shadow-md">
                                <div class="flex flex-1 items-center justify-center">
                                    <span class="text-{{ $colorNilai }}-500 text-xl font-medium uppercase">{{ $item->elements->sum('nilai') }}</span>
                                </div>
                                <div class="text-{{ $colorNilai }}-400 flex flex-col text-left text-xs italic">
                                    <span>Penilaian</span>
                                    <span>Target : </span>
                                    <span>Dinilai : 1 dari 3 </span>
                                </div>
                                <div class="mt-2 text-center">
                                    <button class="text-xs text-gray-300">Detail</button>
                                </div>
                            </div>
                        </div>

                        {{-- Konten Sub Bab --}}
                        <div class="flex-1 cursor-pointer rounded-md border border-gray-200 bg-white p-2 shadow-md">
                            <h3 class="mb-2 font-medium text-indigo-500">
                                {{ $item->no }}. {{ $item->nama }}
                            </h3>

                            <div class="flex flex-col gap-2 text-wrap text-sm text-gray-700">
                                @if ($item->deskripsi)
                                    <p><span class="font-medium">Deskripsi:</span> {!! str($item->deskripsi)->sanitizeHtml() !!}</p>
                                @endif

                                @if ($item->maksud_tujuan)
                                    <p>{!! str($item->maksud_tujuan)->sanitizeHtml() !!}</p>
                                @endif
                            </div>


                            @if (in_array($item->id, $expandedItems ?? []))
                                <div class="mt-4 space-y-3 border-t border-gray-200 pt-4">
                                    <livewire:Akreditasi.Ep.ListEp :babId="$item->id" :key="'table-ep-' . $item->id" />
                                </div>
                            @endif

                            {{-- Load More / Show Less Button --}}
                            <div class="mt-3 flex justify-center border-t border-gray-100 pt-3">

                                <button type="button" wire:click="toggleDetail({{ $item->id }})"
                                    class="group flex w-full items-center justify-center gap-2 rounded-lg bg-white p-2 shadow-sm transition-all duration-300 hover:border-indigo-400 hover:bg-indigo-50 hover:shadow-md active:scale-95">

                                    <span class="text-sm text-gray-500 transition-colors duration-300">
                                        {{ in_array($item->id, $expandedItems ?? []) ? 'Sembunyikan' : 'Tampilkan Detail' }}
                                    </span>

                                    <div class="{{ in_array($item->id, $expandedItems ?? []) ? 'rotate-180' : '' }} flex flex-col -space-y-2 transition-transform duration-200">
                                        <svg class="h-3 w-3 text-gray-400 transition-colors group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                @break

                @default
            @endswitch
        @endforeach
    </div>


    {{-- modal --}}
    <x-filament::modal id="modal-upload-berkas" width="w-full">
        <x-slot:heading>Element Penilaian <span class="text-indigo-500" x-text="selectedBabNama"></span> </x-slot:heading>

        <livewire:Akreditasi.Ep.listEp :babId="$babIdSelected" :key="'element-list' . $babIdSelected" />
    </x-filament::modal>


    <x-filament::modal id="modal-penilaian">
        <x-slot:heading>Penilaian</x-slot:heading>
    </x-filament::modal>
</div>
