<div>
    {{ $this->table }}

    {{-- modal edit --}}
    <x-filament::modal id="edit-jabatan" :autofocus="false">
        <x-slot name="heading">
            Edit Jabatan <span class="font-semibold text-primary-500"> {{ $jabatan?->nama }} </span>
        </x-slot>

        <livewire:Master.Jabatan.Edit :key="Str::random()" :id="$jabatan?->id" @jabatan-updated="$refresh" />

    </x-filament::modal>
</div>
