<div class="flex flex-col space-y-3">

    <div class="flex w-full flex-row rounded-lg bg-white">
        <div class="px-3 py-2">
            @props(['active' => 'text-lg font-bold border-b-4 border-indigo-500/50 bg-indigo-500/15 '])

            <x-ts:button sm flat loading="navigateTo('all')" wire:click="navigateTo('all')" @class([$active => $content === 'all'])>
                <x-ts:icon name="tabler.users" class="h-5 w-5" />
                Semua
            </x-ts:button>


            @can('view-dokter')
                <x-ts:button sm flat loading="navigateTo('dokter')" wire:click="navigateTo('dokter')" @class([$active => $content === 'dokter'])>
                    <x-ts:icon name="tabler.stethoscope" class="h-5 w-5" />
                    Dokter
                </x-ts:button>
            @endcan

        </div>
        <div class="ms-auto px-3 py-2">
            @switch($content)
                @case('all')
                    <x-ts:button sm icon="tabler.user-plus" x-on:click="$dispatch('open-modal', {id:'new-karyawan'})">
                        Karyawan Baru
                    </x-ts:button>
                @break

                @case('dokter')
                    <x-ts:button sm icon="tabler.stethoscope" x-on:click="$dispatch('open-modal', {id:'new-dokter'})">
                        Tambah Dokter
                    </x-ts:button>
                @break

                @default
            @endswitch
        </div>
    </div>

    <div class="rounded-lg bg-white">
        <div class="p-5">
            @switch($content)
                @case('all')
                    <livewire:Karyawan.TableKaryawan :key="Str::random()" />
                @break

                @case('dokter')
                    {{-- <livewire:Karyawan.Dokter.TableDokter :key="Str::random()"> --}}
                @break
            @endswitch
        </div>
    </div>

</div>
