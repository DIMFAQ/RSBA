<div>
    <div class="flex flex-col text-sm">
        <div class="text-semibold flex gap-2 text-red-500">
            <span>Perhatian !</span>
        </div>
        Ketika membuat menu baru, pastikan route tersedia.
    </div>

    <form wire:submit.prevent='submit' class="mt-4 flex flex-col gap-2">
        <x-ts:input wire:model='nama' placeholder="Menu" />
        <div>
            <x-ts:input wire:model.live.debounce.300='route' placeholder="Route (Route Name)" />
            @empty(!$route)
                @if ($route_avail)
                    <span class="text-xs text-primary-500">Route tersedia</span>
                @else
                    <span class="text-xs text-danger-500">Route tidak tersedia</span>
                @endif
            @endempty
        </div>

        <x-ts:input wire:model='icon' placeholder="Icon" hint="Icon libr : tabler.io" />

        <x-ts:select.styled searchable wire:model.defer='parent_id' placeholder="Menu Parent" :options="$parents" select="label:nama|value:id" />


        <x-ts:select.styled searchable wire:model.defer='group' placeholder="Group Menu" :options="$groups" select="label:label|value:value" />

        <div class="ml-auto flex justify-end gap-2">
            <x-ts:button outline x-on:click="$dispatch('close-modal',{id:'add-menu'})">
                Tutup
            </x-ts:button>

            <x-ts:button type="submit" loading="submit" icon="tabler.checks">
                Simpan
            </x-ts:button>
        </div>
    </form>
</div>
