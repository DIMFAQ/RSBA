<div>
    <form wire:submit.prevent='submit' class="flex flex-col gap-4" autocomplete="off">
        <div class="flex w-full flex-col gap-2">

            <div class="flex flex-col gap-1">
                <div class="w-full">
                    <x-ts:input wire:model.lazy='sku' placeholder="SKU" :readonly="$checkboxSku" />
                </div>
                <div class="flex gap-3">
                    <x-ts:toggle wire:model.live='checkboxSku' sm title="On untuk membuat SKU otomatis." />
                    @if ($checkboxSku)
                        <span class="text-xs font-thin italic text-gray-400">SKU akan dbuat otomatis.</span>
                    @else
                        <span class="text-xs font-thin italic text-gray-400">Check box jika SKU ingin generate otomatis..</span>
                    @endif

                </div>
            </div>

            <x-ts:input wire:model.defer='nama' placeholder="Nama Barang" />

            <div class="flex items-center gap-1">
                <div class="w-full">
                    <x-ts:select.styled wire:model.defer='kategori' placeholder="Kategori" searchable :options="$kategoriOptions" select="label:nama|value:id" />
                </div>

                <x-ts:icon role="button" name="tabler.square-plus" class="h-10 w-8 rounded font-thin text-gray-300 hover:text-indigo-400"
                    x-on:click="$dispatch('open-modal',{id:'modal-new-kategori'})" />
            </div>

            <div class="flex items-center gap-1">
                <div class="w-full">
                    <x-ts:select.styled wire:model.defer='satuan' placeholder="Satuan" searchable :options="$satuanOptions" select="label:nama|value:id" />
                </div>

                <x-ts:icon role="button" name="tabler.square-plus" class="h-10 w-8 rounded font-thin text-gray-300 hover:text-indigo-400"
                    x-on:click="$dispatch('open-modal',{id:'modal-new-satuan'})" />
            </div>

            <x-ts:select.styled wire:model='tipe' placeholder="Tipe Barang" searchable :options="$tipeOptions" select="label:label|value:value" />

            <x-ts:number wire:model.defer='min_stok' placeholder="Minimal Stok" type="number" />

            <div class="mt-3 flex-col">
                <x-ts:checkbox sm label="Consumable" wire:model.defer='bhp' />
                <span class="text-xs font-thin italic text-gray-400">Checklist jika barang BHP.</span>
            </div>
        </div>

        <div class="ml-auto flex justify-end gap-2">
            <x-ts:button outline x-on:click="$dispatch('close-modal',{id:'modal-new-barang'})">Tutup</x-ts:button>
            <x-ts:button type="submit" loading="submit" icon="tabler.checks">Simpan</x-ts:button>
        </div>

    </form>


    {{-- modal kategori --}}
    <x-filament::modal id="modal-new-kategori" :close-by-clicking-away="false" :autofocus="false">
        <x-slot name="heading">
            Kategori Baru
        </x-slot>

        <livewire:Master.Barang.Kategori.Add :key="Str::random()" @new-kategori-created="$refresh" />
    </x-filament::modal>


    {{-- modal satuan --}}
    <x-filament::modal id="modal-new-satuan" :close-by-clicking-away="false" :autofocus="false">
        <x-slot:heading>
            Tambah Satuan
        </x-slot:heading>

        <livewire:Master.Barang.Satuan.Add :key="Str::random()" @satuan-created="$refresh" />
    </x-filament::modal>
</div>
