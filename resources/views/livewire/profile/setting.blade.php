<div class="w-full">
    <x-ts:tab selected="Password" x-on:navigate="$wire.switchTab($event.detail.select)">
        <x-ts:tab.items tab="Password">
            <livewire:Profile.GantiPassword />
        </x-ts:tab.items>

        <x-ts:tab.items tab="Role Permission">
            <livewire:Profile.RolePermission />
        </x-ts:tab.items>

        <x-ts:tab.items tab="Email Aktivasi">
        </x-ts:tab.items>

        <x-ts:tab.items tab="Login Session">

        </x-ts:tab.items>
    </x-ts:tab>
</div>
