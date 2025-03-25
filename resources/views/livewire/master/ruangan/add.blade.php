<div>
    <form wire:submit.prevent="submit" class="space-y-2" autocomplete="off">
        @csrf

        <div class="w-full">
            <x-ts:input wire:model.lazy="nama" placeholder="Nama Ruangan" />
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <x-ts:button md outline @click="$dispatch('close-modal',{id:'new-ruangan'})">Tutup</x-ts:button>
            <x-ts:button loading="submit" md type="submit">Simpan</x-ts:button>
        </div>

    </form>
</div>
