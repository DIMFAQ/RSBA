<div class="flex flex-col gap-2">
    <div class="flex w-full justify-end rounded-lg bg-white px-4 py-2">
        <x-ts:button sm x-on:click="$dispatch('open-modal',{id:'modal-new-invoices'})" icon="tabler.file-invoice">
            New Invoices
        </x-ts:button>

    </div>


    {{-- modal new invoice --}}
    <x-filament::modal id="modal-new-invoices" width="w-11/12">
        <x-slot:heading>Inovices Baru</x-slot:heading>

    </x-filament::modal>
</div>
