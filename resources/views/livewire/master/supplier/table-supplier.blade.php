<div>
    {{ $this->table }}


    <x-filament::modal id="modal-edit-supplier" :close-by-clicking-away="false" :autofocus="false">
        <x-slot:heading>Tambah Supplier</x-slot:heading>
        <livewire:Master.Supplier.Edit :key="Str::random()" :id="$selectedId" @new-supplier-updated="$refresh" />
    </x-filament::modal>
</div>
