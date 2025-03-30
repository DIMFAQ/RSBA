<div class="flex-1 overflow-auto p-2">
    <div class="max-h-screen overflow-auto">
        <table class="min-w-full table-fixed border-collapse">
            <thead class="sticky top-0 bg-white">
                <tr class="border-b text-left text-sm text-gray-600">
                    <th class="px-4 py-2">Id</th>
                    <th class="px-4 py-2">No. Rekemdis</th>
                    <th class="px-4 py-2">Nama Pasien</th>
                    <th class="px-4 py-2">Tgl Checkout</th>
                    <th class="px-4 py-2">Sep</th>
                    <th class="px-4 py-2">Kelompok</th>
                    <th class="px-4 py-2">DPJP </th>
                    <th class="px-4 py-2">Anastesi</th>
                </tr>
            </thead>
            <tbody class="overflow-auto">
                @php
                    $currentMonth = null;
                @endphp
                @foreach ($pasien as $item)
                    @php
                        $itemMonth = \Carbon\Carbon::parse($item->tgl_checkout)->format('Y-m');
                    @endphp
                    @if ($currentMonth !== $itemMonth)
                        @if ($currentMonth !== null)
            </tbody>
            @endif
            <tbody>
                <tr class="bg-gray-200">
                    <td colspan="8" class="px-4 py-2 font-bold">
                        {{ \Carbon\Carbon::parse($item->tgl_checkout)->format('F Y') }}</td>
                </tr>
                @php
                    $currentMonth = $itemMonth;
                @endphp
                @endif
                <tr class="border-b bg-white text-left text-sm even:bg-gray-100">
                    <td class="px-4 py-2">{{ $item->id }}</td>
                    <td class="px-4 py-2">{{ $item->no_rekmedis }}</td>
                    <td class="px-4 py-2">{{ $item->nama_pasien }}</td>
                    <td class="px-4 py-2">{{ $item->tgl_checkout }}</td>
                    <td class="px-4 py-2">{{ $item->sep }}</td>
                    <td class="px-4 py-2">{{ $item->kelompok }}</td>
                    <td class="px-4 py-2">{{ $item->dpjp }}</td>
                    <td class="flex flex-row items-center gap-2 px-4 py-2">
                        <input type="text" class="h-8 rounded-md border-2 border-gray-300 p-2" placeholder="Input Dokter" wire:model.live.debounce.300="anastesi.{{ $item->id }}">
                        <x-spinner target="anastesi.{{ $item->id }}" sm />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
