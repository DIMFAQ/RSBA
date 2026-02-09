{{-- <div class="relative bg-white rounded-md shadow-xl p-4 mt-1 border border-indigo-500 transition-transform transform">
    <div class="absolute top-[-12px] left-[3%] transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-b-8 border-transparent border-b-indigo-500">
    </div>
    <div class="flex flex-col gap-3">
        @if ($pembelian->status === 'selesai')
            <livewire:Pembelian.ViewDetailPembelian :id="$pembelian->id" :key="Str::random()" />
        @else
            <livewire:Pembelian.TerimaBarang :$pembelian :key="Str::random()" @penerimaan-beli-saved="$refresh" />
        @endif
    </div>
</div> --}}


<div>
    @if ($pembelian->status === 'selesai')
        <livewire:Pembelian.ViewDetailPembelian :id="$pembelian->id" :key="Str::random()" />
    @else
        <livewire:Pembelian.Penerimaan.TerimaBarang :$pembelian :key="Str::random()" @penerimaan-beli-saved="$refresh" />
    @endif
</div>
