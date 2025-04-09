<>
    <div div class="flex flex-col gap-3">
        <div class="flex w-full flex-col gap-2 lg:grid lg:grid-cols-6">
            <x-ts:date wire:model.live='periode' wire:change='updateDashboard' month-year-only />
            <x-ts:select.styled wire:model.live='layanan' wire:change='updateDashboard' placeholder="Layanan" :options="$layanan_opt" select="label:label|value:value" />
            <x-ts:select.styled wire:model.live='cabar' wire:change='updateDashboard' placeholder="Cara Bayar" :options="$cabar_opt" select="label:label|value:value" />
        </div>

        <div class="flex w-full flex-col gap-2 lg:grid lg:grid-cols-4">

            <x-ts:stats class="bg-blue-200/35" title="Diajukan" :number="$klaimDiajukan" :footer="$pasienDiajukan . ' Pasien'" />

            <x-ts:stats class="bg-green-200/35" title="Desetujui Klaim" :number="$klaimDisetujui" :footer="$pasienDisetujui . ' Pasien'">
                <x-slot:right>
                    <span class="text-xl font-semibold text-primary-500">{{ $prosentaseDisetujui }}</span>
                </x-slot:right>
            </x-ts:stats>

            <x-ts:stats class="bg-red-200/35" title="Pending Klaim" :number="$klaimPending" :footer="$pasienPending . ' Pasien'" />

            <x-ts:stats title="Jasa Dokter" :number="$total_jasa" />
        </div>
    </div>
</>
