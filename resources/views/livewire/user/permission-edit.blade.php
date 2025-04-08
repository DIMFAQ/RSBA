<div class="flex flex-col gap-2" x-data="{ menu: @entangle('menu') }">

    <form wire:submit.prevent='submit' class="no-scrollbar h-screen space-y-2 overflow-auto">
        Role Permission{{ $rolePermission }}

        @foreach ($menus as $menu)
            @if (count($menu->submenus) > 0)
                <table class="w-full rounded-lg">
                    <thead>
                        <tr class="bg-red-100 font-semibold">
                            <td scope="col" class="px-3 py-2">{{ $menu->nama }}</td>
                            <td scope="col" class="w-1/6 px-3 py-2 text-right">Role</td>
                            <td scope="col" class="w-1/6 px-3 py-2 text-right">User</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menu->permission as $permission)
                            <tr class="border-b border-neutral-200 transition duration-300 ease-in-out even:bg-primary-50/35 hover:bg-gray-100 dark:border-white/10">
                                <td scope="col" class="px-3 py-2">{{ $permission }}</td>
                                <td scope="col" class="w-1/6 px-3 py-2 text-right">
                                    <div class="flex justify-end">
                                        <x-ts:checkbox wire:model='rolePermission' value="{{ $permission }}" />
                                    </div>
                                </td>
                                <td scope="col" class="w-1/6 px-3 py-2 text-right">
                                    <div class="flex justify-end">
                                        <x-ts:checkbox wire:model='permission' value="{{ $permission }}" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif($menu->parent_id === $mainMenu->id)
                <table class="w-full rounded-lg">
                    <thead>
                        <tr class="bg-primary-100 font-semibold">
                            <td scope="col" class="px-3 py-2">{{ $menu->nama }}</td>
                            <td scope="col" class="w-1/6 px-3 py-2 text-right">Role</td>
                            <td scope="col" class="w-1/6 px-3 py-2 text-right">User</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menu->permission as $permission)
                            <tr class="border-b border-neutral-200 transition duration-300 ease-in-out even:bg-primary-50/35 hover:bg-gray-100 dark:border-white/10">
                                <td scope="col" class="px-3 py-2">{{ $permission }}</td>
                                <td scope="col" class="w-1/6 px-3 py-2 text-right">
                                    <div class="flex justify-end">
                                        <x-ts:checkbox wire:model='rolePermission' value="{{ $permission }}" />
                                    </div>
                                </td>
                                <td scope="col" class="w-1/6 px-3 py-2 text-right">
                                    <div class="flex justify-end">
                                        <x-ts:checkbox wire:model='permission' value="{{ $permission }}" />
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif


            {{-- submenu --}}
            @foreach ($menu->submenus as $submenu)
                <div class="ml-auto flex w-11/12 justify-end">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-green-100 font-semibold">
                                <td scope="col" class="px-3 py-2">{{ $submenu->nama }}</td>
                                <td scope="col" class="w-1/6 px-3 py-2 text-right">Role</td>
                                <td scope="col" class="w-1/6 px-3 py-2 text-right">User</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submenu->permission as $permission)
                                <tr class="border-b border-neutral-200 transition duration-300 ease-in-out even:bg-primary-50/35 hover:bg-gray-100 dark:border-white/10">
                                    <td scope="col" class="px-3 py-2">{{ $permission }}</td>
                                    <td scope="col" class="w-1/6 px-3 py-2 text-right">
                                        <div class="flex justify-end">
                                            <x-ts:checkbox wire:model='rolePermission' value="{{ $permission }}" />
                                        </div>
                                    </td>
                                    <td scope="col" class="w-1/6 px-3 py-2 text-right">
                                        <div class="flex justify-end">
                                            <x-ts:checkbox wire:model='permission' value="{{ $permission }}" />
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endforeach

        <div class="ml-auto flex justify-end gap-2">
            <x-ts:button outline x-on:click="$dispatch('close-modal',{id:'edit-user-permission'})">Batal</x-ts:button>
            <x-ts:button type="submit" loading="submit">Simpan</x-ts:button>
        </div>
    </form>
</div>
