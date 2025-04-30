<div>
    <form wire:submit.prevent='submit' class="flex flex-col gap-4">
        <div class="flex flex-col gap-2">
            <x-ts:input wire:model.defer='nama' placeholder="Nama" />
            <x-ts:input wire:model.defer='org' placeholder="Org" />
            <x-ts:input wire:model.defer='org_unit' placeholder="Org Unit" />
            <x-ts:input wire:model.defer='email' placeholder="Email" />
            <x-ts:password wire:model.defer='password' placeholder="Password" />
            <x-ts:password wire:model.defer='passwordConfirmation' placeholder="Konfirmasi Password" />


        </div>
        <div class="flex flex-row justify-end gap-2">
            <x-ts:button type="submit" icon="tabler.checks" loading="submit">Create</x-ts:button>
        </div>

    </form>
</div>
