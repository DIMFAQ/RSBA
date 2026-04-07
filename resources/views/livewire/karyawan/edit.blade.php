<div class="w-full">
    <div class="mt-4 flex w-full flex-row">
        <span class="gap-auto text-primary-500 flex rounded-md px-3 py-1 font-semibold hover:bg-red-200/75 hover:text-red-500" role="button" wire:click='directback'>
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
                <h2 class="text-primary-500 text-2xl font-semibold">{{ $karyawan->nama }}</h2>
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
                <x-ts:button sm x-on:click="$tsui.open.modal('modal-resign-karyawan')" class="bg-warning-500 hover:bg-warning-400" icon="tabler.user-minus">
                    Resign
                </x-ts:button>
            </div>
        </div>


        {{-- update data --}}
        {{-- this section can scrollable --}}
        <div class="scrollbar-hidden flex max-h-screen w-full flex-col space-y-3 overflow-y-auto lg:max-h-[calc(100vh-20px)] lg:w-3/4">

            <x-collapsible-card title="Kedinasan" icon="tabler-briefcase" color="indigo">
                <livewire:Karyawan.EditKedinasan :id="$karyawan->id" :key="'dinas-' . $karyawan->id" @new-jabatan-created="$refresh" @status-updated="$refresh" />
            </x-collapsible-card>

            <x-collapsible-card title="Identitas" icon="tabler-user-edit" color="indigo" :defaultOpen="false">
                <livewire:Karyawan.EditIdentitas :id="$karyawan->id" :key="'identitas-' . $karyawan->id" @updated-karywan="$refresh" />
            </x-collapsible-card>

            <x-collapsible-card title="Pendidikan" icon="tabler-school" color="indigo" :defaultOpen="false">
                <livewire:Karyawan.Pendidikan.PendidikanList :id="$karyawan->id" :key="Str::random(5)" @deleted-pendidikan-karyawan="$refresh" @pendidikan-karyawan-created="$refresh" />
            </x-collapsible-card>

            <x-collapsible-card title="Dokumen" icon="tabler-file-type-doc" color="indigo" :defaultOpen="false">
                <livewire:Karyawan.Document.DocumentList :id="$karyawan->id" :key="Str::random(5)" @document-karyawan-created="$refresh" @document-karyawan-deleted="$refresh" />
            </x-collapsible-card>
        </div>
    </div>


    {{-- modal form resign --}}
    <x-ts:modal id="modal-resign-karyawan" center title="Resign" x-on:karyawan-resign-updated.window="$tsui.close.modal('modal-resign-karyawan')">
        {{-- form --}}
        <livewire:Karyawan.Resign :id="$karyawan->id" :key="'modal-resign-' . Str::random(5)" />
    </x-ts:modal>
    {{-- end acton resign --}}
</div>
