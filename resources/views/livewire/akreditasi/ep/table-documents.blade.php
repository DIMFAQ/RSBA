<div>
    {{-- expandable form list --}}
    <div x-data="{ open: false }" class="mb-6">
        {{-- Toggle Button --}}
        <button @click="open = !open" type="button" class="flex w-full items-center justify-between rounded-lg border border-gray-300 bg-white p-2 shadow-sm transition-colors hover:bg-gray-50">
            <span class="text-sm text-gray-700">
                Tambah File
            </span>
            <svg x-bind:class="open ? 'rotate-180' : ''" class="h-5 w-5 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        {{-- Expandable Form --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2" class="mt-2 rounded-lg border border-gray-300 bg-white p-2 shadow-sm">

            <form wire:submit.prevent='submit' autocomplete="off">
                {{-- Isi form Anda di sini --}}
                <div class="space-y-4">
                    {{-- Contoh input --}}

                    <x-ts:input wire:model='nama' label="Nama File" placeholder="Nama File" />
                    <x-ts:upload wire:model='pdf_file' accept=".pdf" label="File" hint="Upload file format .pdf (max: 10mb)" tip="Drag and drop file disini." />

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


                    {{-- Tombol Submit --}}
                    <div class="flex justify-end gap-2">
                        <button @click="open = false" type="button" class="rounded-md bg-gray-100 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-200">
                            Batal
                        </button>
                        <x-ts:button type="submit" loading="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700">
                            Simpan
                        </x-ts:button>
                    </div>
                </div>
            </form>

        </div>
    </div>


    <div>
        {{ $this->table }}
    </div>


    <x-filament::modal id="modal-document-view" width="screen">
        <x-slot:heading>Document </x-slot:heading>

        <livewire:Akreditasi.Ep.Document :docSelectedId="$selectedDocId" :key="'view-doc-' . $selectedDocId" />


    </x-filament::modal>
</div>
