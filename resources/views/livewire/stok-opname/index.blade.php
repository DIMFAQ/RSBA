<div class="flex flex-col gap-2">

    <div class="flex flex-row bg-white rounded-lg py-2 px-4">
        <div>

        </div>
        <div class="flex ml-auto justify-end gap-2">
            <x-ts:button sm icon="tabler.calendar-plus">
                Pelaksanaan
            </x-ts:button>
        </div>
    </div>

    <div class="bg-white rounded-lg py-2 px-4">
        <livewire:StokOpname.TablePelaksanaan :key="Str::random()" />
    </div>
</div>
