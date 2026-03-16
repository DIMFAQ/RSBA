<div class="flex flex-col gap-4">
    <div class="text-sm italic">
        <span>Print pengajuan cuti, minta persetujuan atasan, dan kumpulkan ke bagian SDM.</span>
    </div>

    <div class="flex flex-col gap-4 text-sm lg:flex-row">
        @if (count($this->approvalOptions) > 1)
            <div class="w-full lg:w-1/2">
                <span>Mengetahui</span>
                <x-ts:select.styled wire:model.defer='mengetahui' :options="$this->approvalOptions" select="label:nama|value:value" />
            </div>
        @endif

        {{--  --}}
        <div class="w-full lg:w-1/2">
            <span>Menyetujui</span>
            <x-ts:select.styled wire:model.defer='menyetujui' :options="$this->approvalOptions" select="label:nama|value:value" />
        </div>
    </div>

    <div class="flex justify-end gap-2">
        <x-ts:button sm outline color="dark">Batal</x-ts:button>
        <x-ts:button sm outline icon="tabler.printer" loading="printManual" wire:click="printManual()">Print</x-ts:button>
    </div>
</div>
