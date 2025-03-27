<div>
    {{ $this->table }}


    {{-- modal action table --}}
    {{-- catat asset --}}
    <x-filament::modal id="modal-catat-asset">
        <x-slot:heading>Catat Asset</x-slot:heading>
        <livewire:Asset.Catat :id="$selectedId" :key="Str::random()" @new-asset-created="$refresh" />
    </x-filament::modal>

    {{-- kelengkapan data asset --}}
    <x-filament::modal id="modal-asset-biodata">
        <x-slot:heading>Data Asset</x-slot:heading>

    </x-filament::modal>


    {{-- pencatatan maintenance asset --}}
    <x-filament::modal id="modal-catat-maintenance" width="max-w-5xl">
        <x-slot:heading>
            <div class="flex flex-row items-center gap-2">
                <x-tabler-device-imac-cog class="size-5" />
                Maintenance
            </div>
        </x-slot:heading>
        <livewire:Asset.Maintenance :id="$selectedId" :key="Str::random()" @new-asset-created="$refresh" />
    </x-filament::modal>

    {{-- Modal Logs asset --}}
    <x-filament::modal id="modal-logs-asset" width="max-w-3xl">
        <x-slot:heading>Logs</x-slot:heading>
        <livewire:Asset.Logs :id="$selectedId" :key="Str::random()" />
    </x-filament::modal>
</div>
