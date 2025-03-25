<div class="flex flex-row gap-4">

    <div class="flex w-1/4 flex-col gap-2 rounded-lg bg-indigo-50 p-4">
        <span class="font-semibold text-indigo-500"> Role Anda</span>

        <span>{{ auth()->user()->getRoleNames() }}</span>
    </div>

    <div class="flex w-1/4 flex-col gap-2 rounded-lg bg-gray-50 p-4">
        <span class="font-semibold text-indigo-500">Permisson Default Role</span>
        <ul>
            @foreach (Auth::user()->getPermissionsViaRoles() as $permission)
                <li>{{ $permission->name }}</li>
            @endforeach
        </ul>
    </div>

    <div class="flex w-1/4 flex-col gap-2 rounded-lg bg-gray-50 p-4">
        <span class="font-semibold text-indigo-500">Spesial Permission User</span>
        <ul>
            @foreach (Auth::user()->getAllPermissions() as $item)
                <li>{{ $item->name }}</li>
            @endforeach
        </ul>
    </div>
</div>
