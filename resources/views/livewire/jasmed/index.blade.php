<div class="h-screen">
    <div class="mt-4 flex w-full flex-row rounded-lg bg-white">
        <div class="px-3 py-1">
            @props(['active' => 'font-bold border-b-4 border-indigo-500/50 bg-indigo-200/50'])

            @can('jasmed-bpjs')
                <x-ts:button flat loading="navigateTo('bpjs')" wire:click="navigateTo('bpjs')" @class([$active => $content === 'bpjs'])>
                    BPJS
                </x-ts:button>
            @endcan

            @can('jasmed-tunai')
                <x-ts:button flat loading="navigateTo('tunai')" wire:click="navigateTo('tunai')" @class([$active => $content === 'tunai'])>
                    Tunai
                </x-ts:button>
            @endcan

            @can('jasmed-jkmd')
                <x-ts:button flat loading="navigateTo('jkmd')" wire:click="navigateTo('jkmd')" @class([$active => $content === 'jkmd'])>
                    JKMD
                </x-ts:button>
            @endcan
        </div>
    </div>

    <div class="mt-2 w-full rounded-lg">

        <div class="flex flex-col gap-2 lg:flex-row lg:gap-4">
            @switch($content)
                @case('bpjs')
                    <div class="w-full rounded-lg bg-white p-4 lg:w-1/2">
                        <livewire:jasmed.bpjs-import :$content :key="Str::random()" />
                    </div>

                    <div class="flex w-full flex-col gap-2 lg:w-1/2">
                        <div class="rounded-lg bg-white p-4">
                            <livewire:Jasmed.BpjsRajal :key="Str::random()">
                        </div>
                        <div class="rounded-lg bg-white p-4">
                            <livewire:Jasmed.BpjsRanap :key="Str::random()">
                        </div>
                    </div>
                @break

                @case('tunai')
                    Belum Tersedia
                @break

                @case('jkmd')
                    <div class="w-full rounded-lg bg-white p-4 text-gray-500 lg:w-1/2">
                        <div class="flex flex-col rounded-lg border border-indigo-400 bg-indigo-50 p-2 text-xs">
                            <span class="font-semibold text-indigo-500">Import Data Seperti BPJS</span>
                            <span>Dengan beberapa perbedaan :</span>
                            <ul class="ms-4 list-disc">
                                <li>Generate SEP dengan cara penggabungan data <b>{layanan}MRN-TGLCHECKOUT</b></li>
                                <li>Rumus penggabugan diexcel <b><i>{RI/RJ}{MRN}&"-"&{TGLCHECKOUT}</i></b></li>
                                <li>Real Billing adalah <b>({total RS} - {TO atau NO}) - ({total rs} * 20%)</b></li>
                            </ul>

                        </div>
                        <div class="mt-2">
                            <livewire:jasmed.bpjs-import :$content :key="Str::random()" />
                        </div>
                    </div>
                    <div class="flex w-full flex-col gap-2 lg:w-1/2">
                        <div class="rounded-lg bg-white p-4">
                            <livewire:Jasmed.Jkmd.Rajal :key="Str::random()">
                        </div>
                        <div class="rounded-lg bg-white p-4">
                            <livewire:Jasmed.Jkmd.Ranap :key="Str::random()">
                        </div>
                    </div>
                @break

                @default
                    <div class="flex w-full flex-col gap-4">
                        <div class="w-full rounded-lg bg-white p-4">
                            <livewire:Jasmed.Dashboard :key="Str::random()" />
                        </div>
                    </div>
            @endswitch
        </div>
    </div>

</div>
