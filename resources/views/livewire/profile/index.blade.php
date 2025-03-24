<div>
    <div class="flex flex-col gap-2 lg:flex-row">
        <div class="w-full lg:w-1/4">
            <div class="flex flex-col space-y-2 rounded-lg bg-white p-8">
                <div class="flex flex-col items-center justify-center" x-data="{ userPreview: '{{ $userTmp ? $userTmp->temporaryUrl() : asset('storage' . $user) }}' }">
                    <div class="flex h-44 w-44 cursor-pointer items-center justify-center overflow-hidden rounded-full border border-indigo-300">
                        <img :src="userPreview" class="h-full w-full object-cover" alt="Click to update" x-on:click="document.getElementById('profileInput').click();">
                    </div>

                    <input type="file" wire:model='userTmp' id="profileInput" style="display: none" @change="userPreview = URL.createObjectURL($event.target.files[0])" />

                    @if ($userTmp)
                        <x-ts:button sm wire:click="updateAvatar" loading="updateAvatar">
                            Update
                        </x-ts:button>
                    @endif

                    <span class="text-lg uppercase text-primary-500">
                        {{ $user->karyawan->nama }}
                    </span>
                    <span class="text-md">
                        {{ $user->karyawan->nip }}
                    </span>
                </div>

                <div class="flex">
                    <span class="w-1/4">Jabatan </span> : {{ $user->karyawan->jabatan?->first()->nama ?? '-' }}
                </div>
                <div class="flex">
                    <span class="w-1/4">Status</span> :
                    <x-filament::badge color="{{ $user->karyawan->status->color() }}">
                        {{ $user->karyawan->status->nama() }}
                    </x-filament::badge>
                </div>
                <div class="flex">
                    <span class="w-1/4">Masa Kerja</span> : {{ $user->karyawan->masakerja }}
                </div>
                <div class="flex">
                    <span class="w-1/4">Sisa Cuti</span> : {{ $user->karyawan->cuti }} Hari
                </div>
                <div class="flex">
                    <span class="w-1/4">Role </span> : {{ $user?->getRoleNames()[0] ?? 'Not Assign Roles' }}
                </div>
            </div>
        </div>
        <div class="w-full lg:w-3/4">

            <x-ts:tab selected="Home" x-on:navigate="$wire.set('tab',$event.detail.select)">
                <x-ts:tab.items tab="Home">
                    <x-slot:left>
                        <x-ts:icon name="tabler.home" class="h-5 w-5" />
                    </x-slot:left>

                    <livewire:Profile.Home :id="$user?->karyawan_id">
                </x-ts:tab.items>

                <x-ts:tab.items tab="Identitas">
                    <x-slot:left>
                        <x-ts:icon name="tabler.user-square" class="h-5 w-5" />
                    </x-slot:left>

                    {{-- load edit-identitas --}}
                    <livewire:Karyawan.EditIdentitas :id="$user?->karyawan_id" />
                </x-ts:tab.items>

                <x-ts:tab.items tab="Pendidikan">
                    <x-slot:left>
                        <x-ts:icon name="tabler.school" class="h-5 w-5" />
                    </x-slot:left>

                    {{-- load Karyawan.Pendidikan --}}
                    <livewire:Karyawan.Pendidikan.PendidikanList :id="$user?->karyawan_id" :key="Str::random()" @pendidikan-karyawan-created="$refresh" @deleted-pendidikan-karyawan="$refresh" />

                </x-ts:tab.items>

                <x-ts:tab.items tab="Documents">
                    <x-slot:left>
                        <x-ts:icon name="tabler.file-type-doc" class="h-5 w-5" />
                    </x-slot:left>


                    {{-- load Karyawan.Documents --}}
                    <livewire:Karyawan.Document.DocumentList :id="$user?->karyawan_id" :key="Str::random()" @document-karyawan-created="$refresh" @document-karyawan-deleted="$refresh" />
                </x-ts:tab.items>


                <x-ts:tab.items tab="Cuti">
                    <x-slot:left>
                        <x-ts:icon name="tabler.calendar-pause" class="h-5 w-5" />
                    </x-slot:left>

                    {{-- load Jadwal & Cuti --}}
                    <livewire:Profile.Cuti :id="$user?->karyawan_id" :key="Str::random()">
                </x-ts:tab.items>
            </x-ts:tab>

        </div>
    </div>
</div>
