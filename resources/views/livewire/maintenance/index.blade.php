<div>
    <div class="flex flex-col rounded-md bg-white p-4">

        <x-ts:tab :selected="auth()->user()->can('approval-maintenance') && $this->getHasNewRequestProperty() ? 'Permintaan' : 'Jadwal'">
            @can('approval-maintenance')
                @if ($this->getHasNewRequestProperty())
                    <x-ts:tab.items tab="Permintaan" class="items-center">
                        <x-slot:left>
                            <span class="absolute block h-1 w-1 animate-pulse rounded-full bg-red-500 ring-2 ring-red-300"></span>
                        </x-slot:left>
                        <livewire:Maintenance.Permintaan.ListPermintaan />
                    </x-ts:tab.items>
                @endif
            @endcan

            <x-ts:tab.items tab="Jadwal">
                <livewire:Maintenance.ListJadwal :key="'list-jadwal'" />
            </x-ts:tab.items>

            <x-ts:tab.items tab="Laporan Publik" class="items-center">
                @if ($this->getPendingPublicReportsCountProperty() > 0)
                    <x-slot:right>
                        <span class="inline-flex items-center justify-center rounded-full bg-red-500 px-1.5 py-0.5 text-xs font-bold text-white leading-none">
                            {{ $this->getPendingPublicReportsCountProperty() }}
                        </span>
                    </x-slot:right>
                @endif
                <livewire:Maintenance.PublicReports.Index :key="'public-reports'" />
            </x-ts:tab.items>
        </x-ts:tab>

    </div>
</div>
