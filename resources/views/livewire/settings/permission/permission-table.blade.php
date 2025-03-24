<div>
    {{ $this->table }}


    <x-filament::modal id="modal-edit-permission">
        <x-slot:heading>Edit Permission</x-slot:heading>

        <livewire:Settings.Permission.Edit :id="$selectedPermission" :key="Str::random()" />
    </x-filament::modal>
</div>
