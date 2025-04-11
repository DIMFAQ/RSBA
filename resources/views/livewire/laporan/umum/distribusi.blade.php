<div class="w-full rounded border bg-white p-2" x-show="!$wire.init">
    <span wire:loading class="flex animate-pulse italic text-indigo-500">Loading...</span>

    <span wire:loading.remove class="w-full overflow-y-auto">
        <x-table-static :$headers :$rows :striped />
    </span>

</div>
