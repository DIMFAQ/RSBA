<div class="flex flex-col space-y-3">

    <div class="flex w-full flex-row rounded-lg bg-white">
        <div class="ms-auto px-3 py-2">
            <x-ts:button sm icon="tabler.plus" x-on:click="$dispatch('open-modal',{id:'new-permission'})">
                Tambah
            </x-ts:button>
        </div>
    </div>


    <div class="relative overflow-x-auto rounded-lg bg-white px-4 py-2">
        <livewire:Settings.Permission.PermissionTable :key="Str::random()" />
    </div>


    {{-- modal --}}
    <x-filament::modal id="new-permission" width="xl" :autofocus="false">
        <x-slot name="heading">
            Permission Baru
        </x-slot>
        <livewire:Settings.Permission.Add />

    </x-filament::modal>
</div>
