<div>
    <div class="flex-1 overflow-auto p-2">
        <div class="max-h-screen overflow-auto">
            <table class="min-w-full table-fixed border-collapse">
                <thead class="sticky top-0 bg-white">
                    <tr class="border-b text-left text-sm text-gray-600">
                        <th class="px-4 py-2">Id</th>
                        <th class="px-4 py-2">No. Rekemdis</th>
                        <th class="px-4 py-2">Nama Pasien</th>
                        <th class="px-4 py-2">Tgl Checkin</th>
                        <th class="px-4 py-2">Tgl Checkout</th>
                        <th class="px-4 py-2">Sep</th>
                        <th class="px-4 py-2">DPJP </th>
                        <th class="px-4 py-2">Kelompok</th>
                    </tr>
                </thead>
                <tbody class="overflow-auto">

                    @foreach ($pasien as $item)
                        <tr class="border-b bg-white text-left text-sm even:bg-gray-100">
                            <td class="px-4 py-2">{{ $item->id }}</td>
                            <td class="px-4 py-2">{{ $item->no_rekmedis }}</td>
                            <td class="px-4 py-2">{{ $item->nama_pasien }}</td>
                            <td class="px-4 py-2">{{ $item->tgl_checkin }}</td>
                            <td class="px-4 py-2">{{ $item->tgl_checkout }}</td>
                            <td class="px-4 py-2">{{ $item->sep }}</td>
                            <td class="px-4 py-2">{{ $item->dpjp }}</td>
                            <td class="px-4 py-2">{{ $item->kelompok }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</div>
