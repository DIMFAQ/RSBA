<div>
    <div>{{ $this->table }} </div>


    <x-filament::modal id="modal-document-view" width="screen">
        <x-slot:heading>Document </x-slot:heading>

        <livewire:Akreditasi.Documents.View :docSelectedId="$selectedDocId" :key="'view-doc-' . Str::random()" />
    </x-filament::modal>
</div>
