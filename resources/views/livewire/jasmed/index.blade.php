<div class="h-screen">
    <div class="mt-4 flex w-full flex-row rounded-lg bg-white">
        <div class="px-3 py-1">
            @props(['active' => 'font-bold border-b-4 border-indigo-500/50 bg-indigo-200/50'])

            <x-ts:button flat loading="navigateTo('bpjs')" wire:click="navigateTo('bpjs')" @class([$active => $content === 'bpjs'])>
                BPJS
            </x-ts:button>
            <x-ts:button flat loading="navigateTo('tunai')" wire:click="navigateTo('tunai')" @class([$active => $content === 'tunai'])>
                Tunai
            </x-ts:button>
            <x-ts:button flat loading="navigateTo('jkmd')" wire:click="navigateTo('jkmd')" @class([$active => $content === 'jkmd'])>
                JKMD
            </x-ts:button>
        </div>
    </div>

    <div class="mt-2 w-full rounded-lg">

        <div class="flex flex-col gap-2 lg:flex-row lg:gap-4">
            @switch($content)
                @case('bpjs')
                    <div class="w-full rounded-lg bg-white p-4 lg:w-1/2">
                        <livewire:jasmed.bpjs-import :key="Str::random()" />
                    </div>

                    <div class="flex w-full flex-col gap-2 lg:w-1/2">
                        <div class="rounded-lg bg-white p-4">
                            {{-- <livewire:jasmed.bpjs :key="Str::random()" /> --}}
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
                    Belum Tersedia
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
