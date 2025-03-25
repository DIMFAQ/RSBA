<div class="flex flex-col gap-2 lg:flex-row">
    <div class="w-full rounded-lg bg-white p-4 shadow-md lg:w-1/4" x-data="{ logoPreview: '{{ $logoTmp ? $logoTmp->temporaryUrl() : asset('storage/' . $logo) }}' }">

        <div class="flex flex-col items-center justify-center space-y-2 rounded-lg bg-white">
            {{-- <div class="flex place-self-center"> --}}
            <div class="flex h-full w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg border border-indigo-300">

                <img :src="logoPreview" class="h-full w-full object-cover" alt="Click to update" x-on:click="document.getElementById('logoInput').click();">
            </div>
            <input type="file" wire:model='logoTmp' id="logoInput" style="display: none" @change="logoPreview = URL.createObjectURL($event.target.files[0])" />

            @if ($logoTmp)
                <x-ts:button wire:click="updateLogo" loading="updateLogo" class="h-8 w-full">
                    Update Logo
                </x-ts:button>
            @endif
        </div>
    </div>
    <div class="w-full lg:w-3/4">
        <x-ts:card header="Edit Perusahaan">
            <form wire:submit.prevent='update' autocomplete="off">
                <div class="flex gap-5">
                    <div class="w-1/2 space-y-2">
                        <x-ts:input wire:model.lazy='nama' label="Nama Perusahaan" />
                        <x-ts:input wire:model.lazy='hastags' label="Hastags" />
                        <x-ts:input wire:model.lazy='telp' label="Telp" />
                        <x-ts:input wire:model.lazy='website' label="Website" />
                    </div>
                    <div class="w-1/2 space-y-2">
                        <x-ts:input wire:model.lazy='singkatan' label="Singkatan" />
                        <x-ts:input wire:model.lazy='alamat' label="Alamat" />
                        <x-ts:input wire:model.lazy='email' label="Email" />
                    </div>
                </div>
                <x-ts:button type="submit" loading="update" class="mt-8">
                    Update
                </x-ts:button>
            </form>
        </x-ts:card>
    </div>
</div>
