<div>
    {{ $this->table }}


    <x-filament::modal id="modal-view-document-ep" width="screen">
        <x-slot:heading>Document</x-slot:heading>

        <livewire:Akreditasi.Ep.Document :$docSelectedId :key="'view-doc' . $docSelectedId" />
    </x-filament::modal>


    <x-filament::modal id="modal-manage-document-ep" width="4xl">
        <x-slot:heading>Manage Document</x-slot:heading>

        <livewire:Akreditasi.Ep.TableDocuments :elementId="$elementSelectedId" :key="'manage-doc' . $elementSelectedId" />
    </x-filament::modal>
</div>
