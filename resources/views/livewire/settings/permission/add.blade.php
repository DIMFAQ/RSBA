<form wire:submit.prevent='submit' class="flex flex-col gap-3" autocomplete="off">
    <div class="flex flex-col gap-2">
        <x-ts:input wire:model='nama' placeholder="Nama Permission" />
        <x-ts:input wire:model='guard' placeholder="Guard" />
    </div>

    <div class="mb-3 ml-auto flex gap-2">
        <x-ts:button md outline x-on:click="$dispatch('close-modal',{id:'new-permission'})">Batal</x-ts:button>
        <x-ts:button type="submit" loading="submit">Simpan</x-ts:button>
    </div>
</form>
