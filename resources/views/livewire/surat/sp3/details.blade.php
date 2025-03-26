<div class="flex flex-col gap-2">
    <div class="flex flex-row rounded-md border px-4 py-2 text-indigo-500">
        <div class="flex w-1/4 flex-col">
            <span class="font-semibold">#{{ $suratSp3->no }}</span>
            <span>{{ date('d M Y', strtotime($suratSp3->tgl)) }}</span>
            <span>{{ $suratSp3->method_bayar }}</span>
        </div>
        <div class="flex w-1/4 flex-col">
            <span>{{ $suratSp3->rekanan }}</span>
            <span>{{ $suratSp3->disetujui }}</span>
            <span>{{ $suratSp3->created_by }}</span>
        </div>
        <div class="flex w-3/4 flex-col px-4 py-2">
            <span class="text-xs italic text-gray-500">Subject / Berita</span>
            <span class="font-semibold text-indigo-500">{{ $suratSp3->keterangan ?? '-' }}</span>
        </div>
    </div>

    <div class="mt-2">
        <x-table-static :$headers :$rows headerless />
    </div>

    <div class="mt-2 flex w-full flex-col rounded-md bg-indigo-200/15 px-4 py-2">
        <span class="text-xs italic text-gray-500">Total Pembayaran</span>
        <span class="font-lg font-bold text-indigo-500">{{ formatRupiah($suratSp3->details->sum('nominal')) }}</span>

    </div>

    <div id="print-sp3" class="hidden">
        <livewire:Surat.Sp3.PrintSp3 :$suratSp3 />
    </div>

    <div class="ml-auto flex flex-row justify-end gap-2">
        <x-ts:button outline x-on:click="$dispatch('close-modal',{id:'modal-detail-sp3'})">Tutup</x-ts:button>
        <x-ts:button icon="tabler.printer" x-on:click="printArea('print-sp3')">Print</x-ts:button>
    </div>
</div>
