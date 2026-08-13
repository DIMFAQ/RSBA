<div class="flex h-full w-full flex-col items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-6">

        @if ($submitted)
            {{-- Success State --}}
            <div class="flex flex-col items-center space-y-4 py-6 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">Laporan Terkirim!</h4>
                <p class="text-sm text-gray-600">
                    Terima kasih atas laporan Anda. Tim maintenance kami akan segera menindaklanjuti pengaduan ini.
                </p>
                <div class="w-full pt-2">
                    <x-ts:button wire:click="resetForm" class="w-full" color="indigo">
                        <x-icon name="tabler-plus" class="size-4" />
                        Buat Laporan Baru
                    </x-ts:button>
                </div>
                <a wire:navigate href="{{ route('login') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition duration-150 ease-in-out">
                    ← Kembali ke Login
                </a>
            </div>
        @else
            {{-- Form State --}}
            <div class="space-y-1">
                <h4 class="text-3xl font-bold text-gray-900">Pengaduan Kerusakan</h4>
                <p class="text-sm text-gray-500">Laporkan kerusakan fasilitas / perangkat di ruangan Anda.</p>
            </div>

            <form wire:submit.prevent="submit" class="mt-6 w-full space-y-4">

                {{-- Pilih Ruangan --}}
                <div>
                    <x-ts:select.styled
                        wire:model.live="ruangan_id"
                        label="Ruangan"
                        placeholder="-- Pilih Ruangan --"
                        :options="$ruangans"
                        select="label:label|value:value"
                        searchable
                    />
                </div>

                {{-- Jenis Kerusakan --}}
                <div>
                    <x-ts:select.styled
                        wire:model.live="jenis"
                        label="Jenis Kerusakan"
                        :options="$jenisOptions"
                        select="label:label|value:value"
                    />
                </div>

                {{-- Deskripsi --}}
                <div>
                    <x-ts:textarea
                        wire:model.defer="deskripsi"
                        label="Deskripsi Kerusakan"
                        placeholder="Jelaskan kerusakan yang terjadi secara singkat dan jelas..."
                        rows="4"
                    />
                    <p class="mt-1 text-right text-xs text-gray-400">{{ strlen($deskripsi) }} / 1000 karakter</p>
                </div>

                {{-- Submit --}}
                <div class="pt-1">
                    <x-ts:button type="submit" loading="submit" class="w-full" color="indigo">
                        <x-icon name="tabler-send" class="size-4" />
                        Kirim Laporan
                    </x-ts:button>
                </div>

                <hr class="border-gray-200">
                <div class="flex w-full flex-col">
                    <p class="text-sm leading-5 text-gray-600">Sudah punya akun?</p>
                    <a wire:navigate href="{{ route('login') }}"
                       class="font-medium text-indigo-600 transition duration-150 ease-in-out hover:text-indigo-500">
                        Login di sini
                    </a>
                </div>
            </form>
        @endif

    </div>
</div>
