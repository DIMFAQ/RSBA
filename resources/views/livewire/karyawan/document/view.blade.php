<div class="flex h-screen w-full flex-col">
    <div class="w-full flex-1 overflow-y-auto">
        <embed src="{{ asset('storage/' . $document?->filename) }}" type="application/pdf" class="h-full w-full">
    </div>

    <div class="ml-auto mt-3 flex justify-end">
        <x-ts:button outline sm x-on:click="$dispatch('close-modal',{id:'view-document-karyawan'})">Tutup</x-ts:button>
    </div>

</div>
