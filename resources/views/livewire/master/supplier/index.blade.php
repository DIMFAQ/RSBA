<div class="w-full">
    <div class="flex flex-col gap-2">
        <div class="ml-auto flex w-full justify-end rounded-lg bg-white px-4 py-2">

            <x-ts:button sm x-on:click="$dispatch('open-modal',{id:'modal-new-supplier'})" icon="plus">Baru</x-ts:button>

        </div>
        <div class="relative items-center overflow-x-auto rounded-lg bg-white px-4 py-2">
            <livewire:Master.Supplier.TableSupplier :key="Str::random()" />
        </div>
    </div>


    <x-filament::modal id="modal-new-supplier" :close-by-clicking-away="false" :autofocus="false">
        <x-slot:heading>Tambah Supplier</x-slot:heading>


        <livewire:Master.Supplier.Add :key="Str::random()" @new-supplier-created="$refresh" />
    </x-filament::modal>
</div>
