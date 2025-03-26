<div>
    <div class="flex flex-col gap-2">
        @can('create-cuti-other-karyawan')
            <div class="ml-auto flex w-full justify-end rounded-md bg-white px-4 py-2">
                <x-ts:button sm x-on:click="$dispatch('open-modal',{id:'create-cuti'})" icon="tabler.plus">
                    Buat Cuti
                </x-ts:button>
            </div>
        @endcan

        <div class="w-full rounded-md bg-white p-4">
            <livewire:Surat.Cuti.TableCuti :key="Str::random()">
        </div>

    </div>

    <x-filament::modal id="create-cuti" width="lg" :close-by-clicking-away="false">
        <x-slot name="heading">
            Surat Cuti Baru
        </x-slot>

        <livewire:Surat.Cuti.Add :key="Str::random()" @created-cuti="$refresh" />
    </x-filament::modal>

</div>
