<div class="flex w-full flex-row gap-4">

    <div class="w-full rounded-lg bg-gray-50 p-4 lg:w-1/2">
        <h2 class="flex flex-row items-center gap-2 font-semibold text-indigo-500">
            <x-ts:icon name="tabler.key" class="h-5 w-5" />
            Ganti Password
        </h2>
        <form wire:submit.prevent='gantiPassword' class="mt-4 flex flex-col gap-3" autocomplete="off">
            <x-ts:input type="password" wire:model='current_password' placeholder="Password Lama" />
            <x-ts:input type="password" wire:model='password' placeholder="Password Baru" />
            <x-ts:input type="password" wire:model='password_confirmation' placeholder="Konfirmasi Password Baru" />

            <div class="flex justify-end gap-2">
                <x-ts:button type='submit' loading='gantiPassword'>
                    <x-tabler-checks class="size-5" /> Simpan
                </x-ts:button>
            </div>
        </form>
    </div>
    <div class="w-full lg:w-1/2">

    </div>

</div>
