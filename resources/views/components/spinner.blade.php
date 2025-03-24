@props([
    'xs' => false,
    'sm' => false,
    'md' => true,
    'lg' => false,
    'target' => '',
])


<div class="flex items-center" wire:loading wire:target="{{ $target }}">
    {{-- @if ($sm)
        <div class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-blue-600 rounded-full"
            role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
    @endif

    @if ($md && !$sm && !$lg)
        <div class="animate-spin inline-block size-6 border-[3px] border-current border-t-transparent text-blue-600 rounded-full"
            role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
    @endif


    @if ($lg)
        <div class="animate-spin inline-block size-8 border-[3px] border-current border-t-transparent text-blue-600 rounded-full"
            role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
    @endif --}}
    @if ($xs)
        <div class="inline-block size-2.5 animate-spin rounded-full border-[3px] border-current border-t-transparent text-blue-600" role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
    @elseif ($sm)
        <div class="inline-block size-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-blue-600" role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
    @elseif ($lg)
        <div class="inline-block size-8 animate-spin rounded-full border-[3px] border-current border-t-transparent text-blue-600" role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
    @else
        <div class="inline-block size-6 animate-spin rounded-full border-[3px] border-current border-t-transparent text-blue-600" role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
    @endif

</div>
