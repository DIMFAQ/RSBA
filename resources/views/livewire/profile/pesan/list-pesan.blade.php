<div>
    @php
        $messages = [
            'message1' => 'Pesan 1',
            'message2' => 'Pesan 2',
            'message3' => 'Pesan 3',
            'message4' => 'Pesan 4',
            'message5' => 'Pesan 5',
        ];
    @endphp

    @foreach ($messages as $item)
        <div class="flex flex-row items-center justify-between rounded-md p-2 hover:bg-indigo-200/20" role="button">
            <div class="flex flex-row items-center gap-2">
                <x-ts:avatar borderless src="{{ asset('images/pp.png') }}" alt="" class="h-10 w-10 rounded-full" />
                <div class="flex flex-col">
                    <span class="text-sm font-semibold">{{ $item }}</span>
                    <span class="text-xs text-gray-500">Dari</span>
                </div>
            </div>
            <div class="flex flex-col items-end">
                <span class="text-xs text-gray-500">Status</span>
                <span class="text-xs text-gray-500">Tgl dan Jam</span>
            </div>
        </div>
        <hr>
    @endforeach
</div>
