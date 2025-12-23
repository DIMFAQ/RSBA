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
                        <span class="absolute right-0.5 top-1 block h-1 w-1 rounded-full bg-red-500 ring-2 ring-red-300"></span>

                    </x-ts:button.circle>

                    <x-ts:button.circle flat outline x-on:click="$slideOpen('notif-drawer')" class="relative">
                        <x-tabler-bell />
                        <span class="absolute right-0.5 top-1 block h-1 w-1 rounded-full bg-red-500 ring-2 ring-red-300"></span>
                    </x-ts:button.circle>
                </div>
                <x-ts:dropdown>
                    <x-slot:action>
                        <div role="button" class="flex gap-3" x-on:click="show = !show">
                            <div class="flex flex-col">
                                <span class="text-lg font-semibold">{{ $nama }}</span>
                                <span class="text-xs text-gray-500/80">{{ $email }}</span>
                            </div>
                            <x-ts:avatar :image="$foto" :text="$textFoto" :color="$colorFoto" md borderless="{{ $hasFoto ? true : false }}" />
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
            <div class="scrollbar-hidden relative h-screen w-full flex-col gap-4 overflow-y-auto pb-16">
                <livewire:Profile.Pesan.ListPesan :key="auth()->user()->id" />
            </div>
            <div class="absolute bottom-4 flex w-full gap-2">
                <a href="{{ route('profile.pesan') }}" wire:navigate>
                    <span role="button" class="rounded-lg p-2 text-sm italic hover:bg-indigo-50 hover:text-indigo-500"> Lihat Semua Pesan</span>
                </a>
            </div>
        </x-ts:slide>
    </nav>
</div>
