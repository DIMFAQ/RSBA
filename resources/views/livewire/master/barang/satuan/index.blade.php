<div class="flex flex-col gap-2">
    {{-- action navbar --}}
    <div class="w-full flex justify-end py-2 px-4 bg-white rounded-lg">
        <x-ts:button sm x-on:click="$dispatch('open-modal',{id:'modal-new-satuan'})" icon="tabler.plus">
            Tambah
        </x-ts:button>
    </div>

    {{-- table --}}
    <div class="relative overflow-auto bg-white rounded-lg p-4">
        <livewire:Master.Barang.Satuan.TableSatuan :key="Str::random()">
    </div>


    {{-- modal --}}
    <x-filament::modal id="modal-new-satuan" :close-by-clicking-away="false" :autofocus="false">
        <x-slot:heading>
            Tambah Satuan
        </x-slot:heading>

        <livewire:Master.Barang.Satuan.Add :key="Str::random()" @satuan-created="$refresh" />
    </x-filament::modal>
</div>
