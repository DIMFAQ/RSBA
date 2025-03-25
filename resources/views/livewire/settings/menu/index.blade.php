<div class="flex flex-col space-y-2">

    @can('add-menu')
        <div class="ml-auto flex w-full justify-end rounded-lg bg-white">
            <div class="p-2">
                <x-ts:button sm x-on:click="$dispatch('open-modal',{id:'add-menu'})" icon="tabler.plus">
                    Tambah
                </x-ts:button>
            </div>
        </div>
    @endcan

    <div class="relative items-center justify-center overflow-x-auto rounded-lg bg-white px-4 py-2">
        <livewire:Settings.Menu.MenuTable :key="Str::random()" />
    </div>


    {{-- modal --}}
    <x-filament::modal id="add-menu">
        <x-slot name="heading">
            Tambah Menu
        </x-slot>

        <livewire:Settings.Menu.Add @new-menu-created="$refresh" :key="Str::random()" />

    </x-filament::modal>
</div>
