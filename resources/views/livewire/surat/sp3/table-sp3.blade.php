<div class="w-full">
    {{ $this->table }}


    <x-filament::modal id="modal-detail-sp3" width="max-w-4xl" :autofocus="false">
        <x-slot:heading>SP3</x-slot:heading>
        <livewire:Surat.Sp3.Details :$suratSp3 :key="Str::random()" />
    </x-filament::modal>
</div>
