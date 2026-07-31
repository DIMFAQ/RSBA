<div class="flex flex-col gap-4" x-data="{ tab: @entangle('tab') }">
    <div class="rounded-lg bg-white p-4 shadow-sm">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button wire:click="$set('tab', 'daftar-jabatan')" @click="tab = 'daftar-jabatan'"
                    :class="tab === 'daftar-jabatan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                    Daftar Jabatan
                </button>
                <button wire:click="$set('tab', 'bagan-organisasi')" @click="tab = 'bagan-organisasi'"
                    :class="tab === 'bagan-organisasi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium flex items-center gap-1.5">
                    <span>Bagan Struktur Organisasi</span>
                    <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-bold text-indigo-700">D3 Org Chart</span>
                </button>
            </nav>
        </div>

        <div class="mt-4">
            @if($tab === 'daftar-jabatan')
                <div class="flex flex-col gap-2">
                    <div class="flex w-full flex-row rounded-lg bg-white">
                        <div class="ms-auto px-3 py-2">
                            <x-ts:button sm icon="tabler.user-plus" x-on:click="$dispatch('open-modal', {id:'new-jabatan'})">
                                Jabatan
                            </x-ts:button>
                        </div>
                    </div>

                    <div class="relative items-center overflow-x-auto rounded-lg bg-white px-4 py-2">
                        <livewire:Master.Jabatan.JabatanTable :key="Str::random()" />
                    </div>
                </div>
            @endif

            @if($tab === 'bagan-organisasi')
                <livewire:master.jabatan.hierarchy-chart />
            @endif
        </div>
    </div>

    {{-- Modal new Jabatan --}}
    <x-filament::modal id="new-jabatan" :autofocus="false">
        <x-slot name="heading">
            Jabatan Baru
        </x-slot>
        {{-- form --}}
        <livewire:Master.Jabatan.Add @new-jabatan-created="$refresh" :key="Str::random()" />
    </x-filament::modal>
</div>
