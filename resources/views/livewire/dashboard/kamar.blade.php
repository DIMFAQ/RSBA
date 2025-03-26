<div class="space-y-3">
    <div class="flex flex-col items-center justify-center text-2xl font-bold text-primary-500">
        <span>
            DASHBOARD KAPASITAS TEMPAT TIDUR
        </span>
        <span>
            {{ Str::upper($rs->nama) }}
        </span>
    </div>
    <h1 class="flex flex-row items-center justify-center text-lg text-red-500">
        {{ Carbon\Carbon::now('Asia/Jakarta')->isoFormat('dddd, D MMM Y - HH:mm:ss') }}
    </h1>

    {{-- interval in 5 minutes (300 = in second) --}}
    <div x-data="{ counter: 600 }" x-init="setInterval(() => {
        counter--;
        if (counter <= 0) {
            $wire.getData();
            counter = 600;
        }
    }, 1000)">

        <table class="min-w-full text-left dark:text-white">
            <thead class="border-b border-neutral-200 font-semibold uppercase dark:border-white/10">
                <tr class="bg-primary-100">
                    <th scope="col" class="px-6 py-2">Kelas</th>
                    <th scope="col" class="px-6 py-2">Kapasitas</th>
                    <th scope="col" class="px-6 py-2">Tersedia</th>
                    <th scope="col" class="px-6 py-2">Terisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kapasitas as $item)
                    <tr class="border-b border-neutral-200 transition duration-300 ease-in-out even:bg-primary-50/35 dark:border-white/10">
                        <td class="whitespace-nowrap px-6 py-2">{{ $item['kelas'] }}</td>
                        <td class="whitespace-nowrap px-6 py-2">{{ $item['kapasitas'] }}</td>
                        <td class="whitespace-nowrap px-6 py-2">{{ $item['tersedia'] }}</td>
                        <td class="whitespace-nowrap px-6 py-2">
                            <x-ts:progress.circle :percent="$item['prosentase']" xs :color="$item['prosentase_color']" :stroke-circle="1" :stroke-percent="2" />
                        </td>
                    </tr>
                @empty
                    <tr class="border-b border-neutral-200 transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-white/10 dark:hover:bg-neutral-600">
                        <td colspan="5" class="bg-red-100/75 text-center italic">Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        <div class="flex flex-row justify-end gap-2 pt-4">
            <h3> Data update dalam
                <span class="text-semi-bold" x-text="Math.floor(counter / 60).toString().padStart(2, '0')"></span> :
                <span x-text="(counter % 60).toString().padStart(2, '0')"></span>
            </h3>
        </div>
    </div>

</div>
