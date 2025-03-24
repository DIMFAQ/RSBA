<div>
    <nav class="flex h-16 items-center px-6 text-xl text-primary-700">
        <div x-show="!isOpen()" class="flex flex-row items-center gap-2">
            <a x-show="!isOpen()" @click.prevent="handleOpen()" @keyup.enter="alert('Submitted!')" class="hover:text-danger-500" href="#">
                <div x-data="{ isHover: false }">
                    <x-tabler-menu-2 x-show="!isHover" @mouseover="isHover = true" />
                    <x-tabler-layout-sidebar-left-expand x-show="isHover" @mouseleave="isHover = false" />
                </div>
            </a>
            <a href="">
                <span class="ml-4 hidden font-semibold uppercase text-primary-500 lg:block">{{ $title }}</span>
            </a>
        </div>

        <div class="ml-auto flex">
            <div class="flex items-center">
                <div class="me-6 hidden space-x-4 lg:block">
                    <x-ts:button.circle flat outline x-on:click="$slideOpen('pesan-drawer')" class="relative">
                        <x-tabler-mail />

                        @php
                            $pesan = rand(0, 20);
                            if ($pesan > 9) {
                                $pesan = '9+';
                            }
                        @endphp

                        @if ($pesan > 0)
                            <x-ts:badge color="yellow" text="{{ $pesan }}" round light class="absolute right-0 top-0 -translate-y-1/2 translate-x-1/2 transform" />
                        @endif
                    </x-ts:button.circle>

                    <x-ts:button.circle flat outline x-on:click="$slideOpen('notif-drawer')" class="relative">
                        <x-tabler-bell />

                        @php
                            $notif = rand(0, 20);
                            if ($notif > 9) {
                                $notif = '9+';
                            }
                        @endphp
                        @if ($notif > 0)
                            <x-ts:badge color="yellow" text="{{ $notif }}" round light class="absolute right-0 top-0 -translate-y-1/2 translate-x-1/2 transform" />
                        @endif
                    </x-ts:button.circle>
                </div>

                <x-ts:dropdown>
                    <x-slot:action>
                        <div role="button" class="flex gap-3" x-on:click="show = !show">
                            <div class="flex flex-col">
                                <span class="text-lg font-semibold">{{ Auth::user()->karyawan->nama }}</span>
                                <span class="text-xs text-gray-500/80">{{ Auth::user()->email }}</span>
                            </div>
                            <x-ts:avatar :model="auth()->user()->karyawan" property="nama" color="fff" md />
                            {{-- <x-ts:avatar :model="auth()->user()->karyawan->nama" property="nama" :background="substr(str_shuffle('F0123456789'), 0, 6)" color="fff" md /> --}}
                        </div>
                    </x-slot:action>

                    <a href="{{ route('profile.index') }}" wire:navigate>
                        <x-ts:dropdown.items icon="tabler.user" text="Profile" />
                    </a>
                    <a href="{{ route('profile.pesan') }}" wire:navigate>
                        <x-ts:dropdown.items icon="tabler.mail" text="Pesan" />
                    </a>

                    <a href="{{ route('profile.notif') }}" wire:navigate>
                        <x-ts:dropdown.items icon="tabler.bell" text="Notifikasi" />
                    </a>
                    <a href="{{ route('profile.setting') }}" wire:navigate>
                        <x-ts:dropdown.items icon="tabler.settings" text="Settings" />
                    </a>

                    <x-ts:dropdown.items separator wire:click="logout">
                        <span class="flex gap-2 text-red-500">
                            <x-spinner target="logout" sm />
                            <x-tabler-logout-2 wire:loading.remove wire:target="logout" class="size-5" />
                            Logout
                        </span>
                    </x-ts:dropdown.items>

                </x-ts:dropdown>
            </div>
        </div>
    </nav>

    {{-- drawer notification --}}
    <x-ts:slide id="notif-drawer" blur="md">
        <x-slot:title class="flex gap-2 text-lg">
            <x-tabler-bell class="size-7" />
            Notification
        </x-slot:title>

        <livewire:Profile.Notif :key="auth()->user()->id" />
    </x-ts:slide>


    {{-- drawer Pesan --}}
    <x-ts:slide id="pesan-drawer" blur="md">
        <x-slot:title class="flex gap-2 text-lg">
            <x-tabler-mail class="size-7" />
            Pesan
        </x-slot:title>
        <livewire:Profile.Pesan :key="auth()->user()->id">
    </x-ts:slide>
</div>
