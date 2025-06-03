<div>
    <span class="flex flex-row place-items-center gap-2 text-lg font-semibold text-indigo-500">
        <x-tabler-database-import class="size-5" /> Import Data
    </span>

    <h2 class="font-semibold">Step 1 - Import Data Pasien</h2>
    <label class="text-xs">Import data txt dari inacbg.
        <span role="button" class="text-blue-500" wire:click='downloadTemplate("txt")'>Template</span>
        <x-spinner target='downloadTemplate("txt")' xs />
    </label>
    <div class="w-3/4">
        <x-ts:upload wire:model='excelPasien'>
            <x-slot:footer when-uploaded>
                <x-ts:button wire:click="importPasien" loading="importPasien" icon="tabler.database-import" class="w-full">Upload</x-ts:button>
            </x-slot:footer>
        </x-ts:upload>
    </div>


    <hr class="my-3">
    <h2 class="font-semibold">Step 2 - Import Disetujui</h2>
    <label class="text-xs">Import disetujui.
        <span role="button" class="text-blue-500" wire:click='downloadTemplate("disetujui")'>Template</span>
        <x-spinner target='downloadTemplate("disetujui")' xs />
    </label>
    <div class="w-3/4">
        <x-ts:upload wire:model='excelDisetujui'>
            <x-slot:footer when-uploaded>
                <x-ts:button wire:click="importDisetujui" loading="importDisetujui" icon="tabler.database-import" class="w-full">Upload</x-ts:button>
            </x-slot:footer>
        </x-ts:upload>
    </div>

    <hr class="my-3">
    <h2 class="font-semibold">Step 3 - Import Data Dokter</h2>
    <label class="text-xs">Import data dokter dari simrs.
        <span role="button" class="text-blue-500" wire:click='downloadTemplate("visit")'>Template</span>
        <x-spinner target='downloadTemplate("visit")' xs />
    </label>
    <div class="w-3/4">
        <x-ts:upload wire:model='excelDokter'>
            <x-slot:footer when-uploaded>
                <x-ts:button wire:click="importDokter" loading="importDokter" icon="tabler.database-import" class="w-full">Upload</x-ts:button>
            </x-slot:footer>
        </x-ts:upload>
    </div>


    <span role="button" class="mt-2 text-sm text-indigo-500 hover:rounded-md hover:bg-indigo-100" x-on:click="$dispatch('open-modal',{id:'modalDataDokter'})">Check Data Dokter</span>

    {{-- Modal Check Data Dokter --}}
    <x-filament::modal id="modalDataDokter" width="3/4" class="max-h-screen overflow-auto">
        <x-slot:heading>Data Dokter </x-slot:heading>
        <livewire:Jasmed.Dokter.Index :key="Str::random()" />
    </x-filament::modal>

    <hr class="my-3">
    <h2 class="font-semibold">Step 4 - Import Data Rincian</h2>
    <label class="text-xs">Import data rincian dari inacbg.
        <span role="button" class="text-blue-500" wire:click='downloadTemplate("rincian_inacbg")'>Template</span>
        <x-spinner target='downloadTemplate("rincian_inacbg")' xs />
    </label>
    <div class="w-3/4">
        <x-ts:upload wire:model='excelRincian'>
            <x-slot:footer when-uploaded>
                <x-ts:button wire:click="importRincian" loading="importRincian" icon="tabler.database-import" class="w-full">Upload</x-ts:button>
            </x-slot:footer>
        </x-ts:upload>
    </div>
</div>
