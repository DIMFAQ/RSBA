<div>
    <form wire:submit.prevent='submit' class="flex flex-col gap-4" autocomplete="off">
        <div class="flex flex-col gap-2">
            <x-ts:input placeholder="Supplier" wire:model.defer='nama' />
            <x-ts:input placeholder="Telp" wire:model.defer='telp' />
            <x-ts:input placeholder="Email" wire:model.defer='email' />
            <x-ts:input placeholder="NPWP" wire:model.defer='npwp' />
            <x-ts:input placeholder="Bank" wire:model.defer='bank' />
            <x-ts:input placeholder="No Rekening Bank" wire:model.defer='norek' />
            <x-ts:input placeholder="PIC / Atas Nama" wire:model.defer='an' />
            <x-ts:input placeholder="Alamat" wire:model.defer='alamat' />
        </div>
        <div class="ml-auto flex justify-end gap-2">
            <x-ts:button outline color="neutral" x-on:click="$dispatch('close-modal',{id:'modal-edit-supplier'})">Tutup</x-ts:button>
            <x-ts:button type="submit" loading="submit" icon="tabler.checks">Simpan</x-ts:button>
        </div>

    </form>
</div>
