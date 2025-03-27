<div>
    <form wire:submit.prevent='submit' class="flex flex-col gap-2">
        <div class="rounded-md border border-gray-200 p-2">
            <span class="text-lg font-bold text-indigo-500">{{ $assetBarang->barang->nama }}</span>
            <div class="flex flex-row gap-3 text-sm text-gray-400">
                <span>{{ $assetBarang->barang->kategori->nama }}, </span>

                Di : {{ $assetBarang->ruangan->nama }}
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <x-ts:select.styled wire:model.live.debounce='main' :request="route('api.asset.main_item')" select="label:label|value:value" placeholder="Pilih Induk Item" unfiltered />


            <x-ts:date wire:model.defer='tgl_catat' placeholder="Tgl Pencatatan" />

            <x-ts:select.styled wire:model.defer='status' :options="$statusOptions" />

            <x-ts:input wire:model.defer='keterangan' placeholder="Keterangan" />


        </div>
        <div class="mt-4 flex justify-end gap-2">
            <x-ts:button type="submit" loading="submit" icon="tabler.checks">
                Simpan
            </x-ts:button>
        </div>

    </form>
</div>
