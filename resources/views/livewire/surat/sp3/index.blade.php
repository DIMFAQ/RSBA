<div class="flex flex-col gap-2">
    <div class="flex w-full items-center rounded-md bg-white px-4 py-2">
        <div class="ml-auto justify-between">
            <x-ts:button sm outline icon="tabler.checks" x-on:click="$dispatch('open-modal',{id:'modal-verify-sp3'})">
                Verify
            </x-ts:button>

            <x-ts:button sm icon="tabler.plus" x-on:click="$dispatch('open-modal',{id:'modal-add-sp3'})">
                Tambah
            </x-ts:button>
        </div>
    </div>
    <div class="items-center overflow-x-auto rounded-lg bg-white px-4 py-2">
        <livewire:Surat.Sp3.TableSp3 :key="Str::random()" />
    </div>

    <x-filament::modal id="modal-verify-sp3" width="max-w-4xl" :close-by-clicking-away="false">
        <x-slot:heading>Verifi SP3</x-slot:heading>

        <livewire:Surat.Sp3.Verify key="verify-sp3" />
    </x-filament::modal>


    <x-filament::modal id="modal-add-sp3" width="max-w-4xl" x-on:created-sp3="$dispatch('close-modal',{id:'modal-add-sp3'})" :close-by-clicking-away="false">
        <x-slot:heading>Buat SP3</x-slot:heading>

        <livewire:Surat.Sp3.Add key="new-sp3" />
    </x-filament::modal>

</div>
