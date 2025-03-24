<div>
    <div class="items-center px-4 py-2">
        {{ $this->table }}
    </div>

    {{-- modal --}}
    <x-filament::modal id="set-user-role">
        <x-slot name="heading">
            Set Role <span class="text-primary-500">{{ $user?->karyawan->nama }}</span>
        </x-slot>

        <livewire:User.SetRole @updated-role-user="$refresh" :id="$user?->id" :key="Str::random()">
    </x-filament::modal>


    <x-filament::modal id="edit-user-permission" width="lg">
        <x-slot name="heading">
            User Permission <span class="text-primary-500">{{ $user?->karyawan->nama }}</span>
        </x-slot>

        <livewire:User.PermissionEdit @updated-permission-user="$refresh" :id="$user?->id" :key="Str::random()">
    </x-filament::modal>
</div>
