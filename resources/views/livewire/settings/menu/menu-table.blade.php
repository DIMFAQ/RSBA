<div>
    {{ $this->table }}

    <x-filament::modal id="edit-menu" width="xl">
        <x-slot name="heading">
            Edit Menu : <span class="text-primary-500">{{ $menu?->nama }}</span>
        </x-slot>

        {{-- livewire menu edit --}}
        <livewire:Settings.Menu.Edit @menu-updated="$refresh" :id="$menu?->id" :key="Str::random()" />
    </x-filament::modal>
</div>
