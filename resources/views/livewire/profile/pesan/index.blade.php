<div class="flex w-full flex-col gap-4 lg:flex-row">
    <div class="w-full rounded-md bg-white p-2 lg:w-1/4">
        <livewire:Profile.Pesan.ListPesan />
    </div>
    <div class="w-full rounded-md bg-white px-4 py-2 lg:w-3/4">
        <div class="flex flex-col gap-2">
            <div class="flex flex-row items-center justify-between">
                <span class="text-lg font-semibold">Pesan</span>
                <span class="text-xs text-gray-500">Tgl dan Jam</span>
            </div>
            <div class="flex flex-row items-center gap-2">
                <x-ts:avatar sm borderless src="{{ asset('images/pp.png') }}" alt="" class="h-10 w-10 rounded-full" />
                <div class="flex flex-col">
                    <span class="text-sm font-semibold">Pesan 1</span>
                    <span class="text-xs text-gray-500">Dari</span>
                </div>

            </div>
        </div>
        <div class="mt-4 flex flex-col gap-2">
            <span class="text-sm text-gray-500">Balas Pesan</span>
            <textarea class="w-full rounded-md border border-gray-300 p-2" rows="4" placeholder="Tulis pesan..."></textarea>
            <x-ts:button icon="tabler.send-2">Kirim</x-ts:button>
        </div>
    </div>


</div>
