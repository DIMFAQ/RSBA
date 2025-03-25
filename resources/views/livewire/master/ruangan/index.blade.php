<div class="flex flex-col gap-2">

    <div class="flex w-full flex-row rounded-lg bg-white">
        <div class="ms-auto px-3 py-2">
            <x-ts:button sm icon="tabler.plus" x-on:click="$dispatch('open-modal', {id:'new-ruangan'})">
                {{-- <x-tabler-user-plus clas /> --}}
                Ruangan
            </x-ts:button>
        </div>
    </div>

    <div class="relative items-center overflow-x-auto rounded-lg bg-white px-4 py-2">
        {{ $this->table }}
    </div>

    {{-- Modal new karyawan --}}
    <x-filament::modal id="new-ruangan" width="md" :autofocus="false">
        <x-slot name="heading">
            Ruangan Baru
        </x-slot>
        <livewire:Master.Ruangan.Add @new-ruangan-created="$refresh" />
    </x-filament::modal>


    <x-filament::modal id="modal-edit-ruangan" width="md" :autofocus="false">
        <x-slot name="heading">
            Edit Ruangan
        </x-slot>
        {{-- form --}}
        <livewire:Master.Ruangan.Edit :id="$selectedId" :key="Str::random()" @new-ruangan-updated="$refresh" />
    </x-filament::modal>
</div>
