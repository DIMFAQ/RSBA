<div class="flex flex-col gap-4">
    <div div class="flex flex-col gap-3">
        <div class="flex w-full flex-col gap-2 lg:grid lg:grid-cols-6">
            <x-ts:date wire:model.live='periode' wire:change='updateDashboard' month-year-only />

            <x-ts:select.styled wire:model.live='layanan' wire:change='updateDashboard' placeholder="Layanan" :options="$layanan_opt" select="label:label|value:value" />

            <x-ts:select.styled wire:model.live='cabar' wire:change='updateDashboard' placeholder="Cara Bayar" :options="$cabar_opt" select="label:label|value:value" />

            <x-ts:select.styled wire:model.live='batch' wire:change='updateDashboard' placeholder="Batch" :options="$batchOptions" select="label:label|value:value" />
        </div>

        <div class="flex w-full flex-col gap-2 lg:grid lg:grid-cols-4">

            {{-- @dd($this->getStatsPasien()) --}}
            {{-- $klaimDiajukan --}}
            <x-ts:stats class="bg-blue-200/35" title="Diajukan" :number="$this->getStats['totalDiajukan']" :footer="$this->getStats['pasienDiajukan'] . ' Pasien'" />

            <x-ts:stats class="bg-green-200/35" title="Desetujui Klaim" :number="$this->getStats['totalDisetujui']" :footer="$this->getStats['pasienDisetujui'] . ' Pasien'">
                <x-slot:right>
                    <span class="text-primary-500 text-xl font-semibold">{{ $this->getStats['persenteseDisetujui'] }}</span>
                </x-slot:right>
            </x-ts:stats>

            <x-ts:stats class="bg-red-200/35" title="Pending Klaim" :number="$this->getStats['totalPending']" :footer="$this->getStats['pasienPending'] . ' Pasien'" />

            <x-ts:stats title="Jasa Dokter" role="button" wire:click="detail('dokter')" :number="$this->getStatsJasa" />
        </div>
    </div>


    <div class="w-full">
        @switch($content)
            @case('dokter')
                <div class="rounded-md border border-gray-300 bg-gray-50 p-2">
                    <span class="italic text-indigo-500">Total Jasa Per Dokter</span>
                    <livewire:Jasmed.DetailsJasaDokter :$periode :$layanan :$cabar :$batch :key="'detail-jasa-dokter' . md5($periode . $layanan . $cabar . $batch)" />
                </div>
            @break

            @case('pending')
            @break

            @default
        @endswitch
    </div>
</div>
