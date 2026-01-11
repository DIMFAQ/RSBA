<div>
    <form wire:submit.prevent='submit' autocomplete="off" enctype="multipart/form-data">
        <div class="space-y-2">

            <x-ts:input wire:model='nama' label="Nama File" placeholder="Nama File" />

            <div class="grid w-full grid-cols-3 gap-2">
                {{-- <x-ts:input wire:model='nama' label="Chapter" placeholder="Chapter" /> --}}
                <x-ts:select.styled x-on:select="$wire.set('chapter_id',$event.detail.select.id)" searchable :request="route('api.akreditasi.chapters', ['kegiatan' => $kegiatan_id])" select="label:singkatan|value:id" placeholder="Chapter"
                    wire:key='chapter-select-{{ $kegiatan_id }}' />


                <x-ts:select.styled wire:model.blur="sub_id" searchable :request="route('api.akreditasi.babs', ['type' => 'sub', 'chapter' => $chapter_id])" select="label:nama|value:id" :disabled="!$chapter_id" placeholder="Chapter"
                    wire:key='sub-select-{{ $chapter_id }}' />

                <x-ts:select.styled wire:model.blur="element_id_link" searchable :request="route('api.akreditasi.elements', ['sub' => $sub_id])" select="label:singkatan|value:id" :disabled="!$sub_id" placeholder="Chapter"
                    wire:key="element-select-{{ $sub_id }}" />

            </div>
            <div class="flex flex-col">
                <span class="text-xs italic">List Document</span>
                <div>

                </div>
            </div>

            @error('pdf_file')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            {{-- Loading Indicator --}}
            <div wire:loading wire:target="pdf_file" class="text-sm text-blue-600">
                Memproses file...
            </div>

            {{-- Upload Progress --}}
            <div wire:loading wire:target="submitManual" class="text-sm text-blue-600">
                Mengupload file...
            </div>



            <div class="flex justify-end gap-2">
                <button @click="open = false" type="button" class="rounded-md bg-gray-100 px-2 py-1 text-sm text-gray-700 transition-colors hover:bg-gray-200">
                    Batal
                </button>
                <x-ts:button type="submit" loading="submit" class="rounded-md bg-blue-600 px-2 py-1 text-sm text-white transition-colors hover:bg-blue-700">
                    Attach
                </x-ts:button>
            </div>
        </div>
    </form>
</div>
