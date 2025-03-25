<div class="flex flex-col space-y-2">
    <div class="flex w-full flex-row rounded-lg bg-white">
        <div class="ms-auto px-3 py-2">
            <x-ts:button sm icon="tabler.plus" x-on:click="$dispatch('open-modal', {id:'new-role'})">
                Tambah
            </x-ts:button>
        </div>
    </div>

    <div class="relative items-center justify-center overflow-x-auto bg-white px-4 py-2">
        {{ $this->table }}
    </div>

    {{-- modal --}}
    <x-filament::modal id="new-role" width="xl" :autofocus="false">
        <x-slot name="heading">
            Role Akses Baru
        </x-slot>
        <livewire:Settings.Role.Add :key="Str::random()" @new-role-created="$refresh" />
    </x-filament::modal>


    <x-filament::modal id="set-permission" width="xl" :autofocus="false">
        <x-slot name="heading">
            Set Permission Ke Role <span class="text-lg text-primary-500"> {{ $role?->name }}</span>
        </x-slot>

        <livewire:Settings.Role.SetPermission :id="$role?->id" :key="Str::random()" />

    </x-filament::modal>
</div>
