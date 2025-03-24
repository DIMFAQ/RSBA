<div>
    <form wire:submit.prevent='submit' class="flex flex-col gap-4">
        <div class="flex flex-col gap-2">
            <x-ts:input wire:model.defer='nama' placeholder="Nama Permission" />
        </div>
        <div class="ml-auto flex justify-end gap-2">
            <x-ts:button outline x-on:click="$dispatch('close-modal',{id:'modal-edit-permission'})">Tutup</x-ts:button>
            <x-ts:button type="submit" loading="submit" icon="tabler.checks">Simpan</x-ts:button>

        </div>

    </form>
</div>
