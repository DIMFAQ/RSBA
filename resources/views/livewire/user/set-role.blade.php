<div>
    <div class="flex flex-col gap-2">
        <span> Berikan Role :</span>
        <form wire:submit.prevent='submit' class="flex flex-col gap-2">
            @foreach ($roles as $item)
                <div class="rounded-lg p-1 hover:bg-primary-100">
                    <x-ts:radio wire:model.defer="role" id="{{ $item->name }}" value="{{ $item->name }}" label="{{ $item->name }}" />
                </div>
            @endforeach

            <div class="ml-auto flex justify-end gap-2">
                <x-ts:button outline x-on:click="$dispatch('close-modal',{id:'set-user-role'})">Batal</x-ts:button>
                <x-ts:button type="submit" loading="submit" icon="tabler.checks">Simpan</x-ts:button>
            </div>
        </form>
    </div>
</div>
