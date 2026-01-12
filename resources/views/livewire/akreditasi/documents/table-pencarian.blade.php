<div>
    {{ $this->table }}

    <x-filament::modal id="modal-view-document-search" width="screen">
        <x-slot:heading>Document</x-slot:heading>

        <livewire:Akreditasi.Documents.View :$docSelectedId :key="'doc-view-search-' . $docSelectedId" />
    </x-filament::modal>
</div>
