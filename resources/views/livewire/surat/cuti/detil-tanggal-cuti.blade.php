@php
    use Carbon\Carbon;
@endphp
<div class="flex flex-col gap-2">
    <span class="text-lg font-semibold text-primary-500">{{ $surat->karyawan?->nama }}</span>
    <span>No Surat : {{ $surat->no_surat }}</span>
    <ul class="mt-3 list-disc ps-4">
        @foreach (json_decode($surat->tgl_cuti, true) as $tanggal)
            <li>{{ Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d M Y') }}</li>
        @endforeach
    </ul>
</div>
