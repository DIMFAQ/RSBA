<div class="flex flex-col gap-1 p-1 text-sm">

    @foreach ($getRecord()->files as $item)
        {{-- x-on:click="$wire.set('docSelectedId',{{ $item->id }});$dispatch('open-modal',{id:'modal-view-document-ep'})" --}}
        <span class="flex flex-col rounded-md border border-gray-200 px-1 py-0" role="button" wire:click="modalViewDocument({{ $item->id }},'modal-view-document-ep')">
            <span class="hover:text-indigo-500">
                {{ $item->nama }}
            </span>
            <span class="text-italic text-[10px] text-gray-300">{{ $item->user_upload }}</span>
        </span>
    @endforeach
</div>
