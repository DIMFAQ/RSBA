<div class="flex flex-col space-y-3">

    <div class="flex w-full flex-row rounded-lg bg-white">
        <div class="px-3 py-2">
            @props([
                'active' => 'text-lg font-bold border-b-4 border-indigo-500/50 bg-indigo-500/15 ',
                'activeResign' => 'text-lg font-bold border-b-4 border-red-500/50 bg-red-500/15 ',
            ])

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

            <x-ts:button sm flat color="red" loading="navigateTo('resign')" wire:click="navigateTo('resign')" @class([$activeResign => $content === 'resign'])>
                <x-ts:icon name="tabler.briefcase-off" class="h-5 w-5" />
                Resign
            </x-ts:button>

        </div>
        <div class="ms-auto px-3 py-2">
            @switch($content)
                @case('all')
                    <div class="flex flex-row items-center justify-center space-x-2">
                        <x-ts:button sm icon="tabler.user-plus" x-on:click="$dispatch('open-modal', {id:'new-karyawan'})">
                            Karyawan Baru
                        </x-ts:button>

                        @can('export-karyawan')
                            <x-ts:dropdown>
                                <x-slot:action>
                                    <x-ts:icon name="tabler.dots-vertical" role="button" class="text-indigo-500" x-on:click="show = !show" />
                                </x-slot:action>
                                <x-ts:dropdown.items icon="tabler.upload" text="Import Karyawan" x-on:click="$dispatch('open-modal',{id:'import-karyawan'})" />
                                <x-ts:dropdown.items icon="tabler.download" text="Export Karyawan" separator wire:click='downloadKaryawan' />
                            </x-ts:dropdown>
                        @endcan
                    </div>
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
                    <livewire:Karyawan.Dokter.TableDokter :key="Str::random()" />
                @break

                @case('resign')
                    <livewire:Karyawan.TableResign key="table-resign" />
                @break
            @endswitch
        </div>
    </div>

</div>
