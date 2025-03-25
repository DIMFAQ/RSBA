<div class="w-full">
    <div class="mt-4 flex w-full flex-row">
        <span class="gap-auto flex rounded-md px-3 py-1 font-semibold text-primary-500 hover:bg-red-200/75 hover:text-red-500" role="button" wire:click='directback'>
            <x-tabler-chevron-left />
            {{ __('Kembali') }}
        </span>
    </div>

    <div class="mt-4 flex flex-col gap-5 lg:flex-row">
        {{-- overview --}}
        {{-- this section is fixed or not scrollable --}}
        <div class="flex w-full flex-col rounded-lg bg-white lg:sticky lg:top-5 lg:w-1/4">
            <div class="flex flex-col items-center justify-center p-4">
                <x-ts:avatar image="" class="h-40 w-40" />
                <h2 class="text-2xl font-semibold text-primary-500">{{ $karyawan->nama }}</h2>
                <h2 class="text-lg">{{ $karyawan->nip }}</h2>
            </div>

            <div class="flex flex-col space-y-2 border-t-2 border-gray-200/75 p-4">
                <div class="flex">
                    <span class="w-24">Nama</span> : {{ $karyawan->gelar_depan }}
                    {{ $karyawan->nama }},
                    {{ $karyawan->gelar_belakang }}, {{ $karyawan->gelar_belakang2 }}
                </div>
                <div class="flex">
                    <span class="w-24">Jabatan </span> : {{ $karyawan->jabatan?->first()->nama ?? '-' }}
                </div>
                <div class="flex">
                    <span class="w-24">Status </span> :
                    <x-filament::badge color="{{ $karyawan->status->color() }}">
                        {{ $karyawan->status->nama() }}
                    </x-filament::badge>
                </div>
                <div class="flex">
                    <span class="w-24">Masa Kerja </span> : {{ $karyawan->masakerja }}
                </div>
                <div class="flex">
                    <span class="w-24">Usia</span> : {{ $karyawan->usia }}
                </div>
                <div class="flex">
                    <span class="w-24">Kontak </span> : {{ $karyawan->hp . ', ' . $karyawan->hp2 }}
                </div>
                <div class="flex">
                    <span class="w-24">Email </span> :
                    {{ $karyawan->user->email ?? 'Belum Register' }}
                </div>
            </div>

            <div class="flex flex-row gap-2 border-t-2 border-gray-200/75 p-4">
                <x-ts:button sm loading="delete({{ $karyawan->id }})" wire:click="delete({{ $karyawan->id }})" class="bg-red-500 hover:bg-red-500/75" icon="tabler.trash">
                    Hapus
                </x-ts:button>

                {{-- action resign --}}
                <x-ts:button sm x-on:click="$modalOpen('modal-resign-karyawan')" class="bg-warning-500 hover:bg-warning-400" icon="tabler.user-minus">
                    Resign
                </x-ts:button>
            </div>
        </div>


        {{-- update data --}}
        {{-- this section can scrollable --}}
        <div class="no-scrollbar flex max-h-screen w-full flex-col space-y-3 overflow-y-auto lg:max-h-[calc(100vh-20px)] lg:w-3/4">

            <x-ts:card minimize>
                <x-slot:header>
                    <div class="flex flex-row items-center gap-2 text-indigo-500">
                        <x-tabler-briefcase class="h-5 w-5" />
                        Kedinasan
                    </div>
                </x-slot:header>

                <livewire:Karyawan.EditKedinasan :id="$karyawan->id" :key="'dinas-' . $karyawan->id" @new-jabatan-created="$refresh" @status-updated="$refresh">
            </x-ts:card>

            <x-ts:card minimize>
                <x-slot:header>
                    <div class="flex flex-row items-center gap-2 text-indigo-500">
                        <x-tabler-user-edit class="h-5 w-5" />
                        Identitas
                    </div>
                </x-slot:header>

                <div class="flex flex-col lg:flex-row">
                    <livewire:Karyawan.EditIdentitas :id="$karyawan->id" :key="'identitas-' . $karyawan->id" @updated-karywan="$refresh">
                </div>
            </x-ts:card>

            <x-ts:card minimize="mount">
                <x-slot:header>
                    <div class="flex flex-row items-center gap-2 text-indigo-500">
                        <x-tabler-school class="h-5 w-5" />
                        Pendidikan
                    </div>
                </x-slot:header>
                <div>
                    {{-- component form update --}}
                    <livewire:Karyawan.Pendidikan.PendidikanList :id="$karyawan->id" :key="Str::random()" @deleted-pendidikan-karyawan="$refresh" @pendidikan-karyawan-created="$refresh" />
                </div>
            </x-ts:card>


            <x-ts:card minimize="mount">
                <x-slot:header>
                    <div class="flex flex-row items-center gap-2 text-indigo-500">
                        <x-tabler-file-type-doc class="h-5 w-5" />
                        Documents
                    </div>
                </x-slot:header>
                <div>
                    {{-- component form update --}}
                    <livewire:Karyawan.Document.DocumentList :id="$karyawan->id" :key="Str::random()" @document-karyawan-created="$refresh" @document-karyawan-deleted="$refresh" />
                </div>
            </x-ts:card>
        </div>
    </div>


    {{-- modal form resign --}}
    <x-ts:modal id="modal-resign-karyawan" center title="Resign">
        {{-- form --}}
        <livewire:Karyawan.Resign @karyawan-resign-updated="$refresh" :id="$karyawan->id" :key="'modal-' . Str::random()" />
    </x-ts:modal>
    {{-- end acton resign --}}
</div>
