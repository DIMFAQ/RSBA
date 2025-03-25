<div>
    <div class="flex h-full w-full flex-col items-start justify-start p-10 lg:p-16 xl:p-24">
        <h4 class="w-full text-3xl font-bold">Login</h4>

        <form wire:submit.prevent="submit" class="relative mt-10 w-full space-y-3" autocomplete="off">
            {{-- <div class=""> --}}
            <div class="relative">
                <x-ts:input wire:model.defer='email' label="Email" placeholder="Email" />
            </div>
            <div class="relative">
                <x-ts:password wire:model.defer='password' label="Password" placeholder="Password" />
            </div>
            <div class="relative">
                <x-ts:button type="submit" loading="submit" class="w-full">
                    <x-icon name="tabler-key" class="size-4" />
                    Login
                </x-ts:button>
            </div>

            <hr class="my-12">
            <div class="flex w-full flex-row">
                <div class="w-1/2">
                    <p class="max-w text-sm leading-5 text-gray-600">
                        Belum mempunyai akun ?
                    </p>
                    <a wire:navigate href="{{ route('register') }}" class="font-medium text-indigo-600 transition duration-150 ease-in-out hover:text-indigo-500 focus:underline focus:outline-none">
                        Registrasi
                    </a>
                </div>
            </div>

        </form>

    </div>
</div>
