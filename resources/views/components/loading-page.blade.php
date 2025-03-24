@props(['balls' => 4])
<div id="loader-page" class="fixed left-0 top-0 z-50 flex h-full w-full items-center justify-center bg-white opacity-50">
    <div class="z-50 flex h-full w-full items-center justify-center">

        {{-- loading by daisy ui --}}
        {{-- <span class="loading loading-dots bg-base-100 loading-lg"></span> --}}

        {{-- custom --}}
        <div class="la-ball-elastic-dots la-xl text-indigo-500">
            @for ($i = 0; $i < $balls; $i++)
                <div>.</div>
            @endfor
        </div>

    </div>
</div>

@push('script')
    <script>
        document.addEventListener('livewire:navigate', function() {
            document.querySelector('#loader-page').style.display = 'block';
        }, {
            once: true
        });


        document.addEventListener('livewire:navigated', function() {
            document.querySelector('#loader-page').style.display = 'none';
        })
    </script>
@endpush
