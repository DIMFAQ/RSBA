<div>
    <form wire:submit.prevent="submit" class="flex flex-col gap-2" autocomplete="off">
        @csrf

        <div class="flex w-full flex-col gap-2">
            <x-ts:input wire:model.defer="nama" placeholder="Nama Bagian / Divisi" />

            <x-ts:select.styled placeholder="Pilih Kelompok" wire:model.defer='group' :options="$groups" select="label:label|value:value" />
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <x-ts:button md outline @click="$dispatch('close-modal',{id:'new-bagian'})">Tutup</x-ts:button>
            <x-ts:button loading="submit" md type="submit">Simpan</x-ts:button>
        </div>
    </form>
</div>
