<div class="flex flex-col gap-4">

    <div class="flex flex-col rounded-lg bg-indigo-50 p-4">
        <span class="text-sm text-gray-400">Role anda</span>
        <span class="text-lg font-semibold text-indigo-500">{{ $role }}</span>
    </div>

    <span class="flex flex-row gap-4">
        <div class="flex w-1/2 flex-col gap-2 rounded-lg bg-gray-50 p-4">
            <span class="font-semibold text-indigo-500">Permisson Default Role</span>
            <ul class="ms-4">
                @forelse ($permissionInRole as $permission)
                    <li>{{ $permission->name }}</li>
                @empty
                    <li class="text-sm italic text-gray-400">Tidak memiliki permission default.</li>
                @endforelse

            </ul>
        </div>

        <div class="flex w-1/2 flex-col gap-2 rounded-lg bg-gray-50 p-4">
            <span class="font-semibold text-indigo-500">Spesial Permission User</span>
            <ul class="ms-4">
                @forelse ($specialPermission as $item)
                    <li>{{ $item->name }} </li>
                @empty
                    <li class="text-sm italic text-gray-400">Tidak memiliki permission khusus.</li>
                @endforelse
            </ul>
        </div>
    </span>
</div>
