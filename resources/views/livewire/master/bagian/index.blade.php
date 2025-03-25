<div class="flex flex-col gap-2">

    <div class="flex w-full flex-row rounded-lg bg-white">
        <div class="ms-auto px-3 py-2">
            <x-ts:button sm icon="tabler.plus" x-on:click="$dispatch('open-modal', {id:'new-bagian'})">
                Tambah
            </x-ts:button>
        </div>
    </div>

    <div class="relative overflow-x-auto rounded-lg bg-white px-4 py-2">
        {{ $this->table }}
    </div>

    {{-- Modal new karyawan --}}
    <x-filament::modal id="new-bagian" width="md" :autofocus="false">
        <x-slot name="heading">
            Bagian Baru
        </x-slot>
        {{-- form --}}
        <livewire:Master.Bagian.Add lazy @new-bagian-created="$refresh" />
    </x-filament::modal>
</div>
