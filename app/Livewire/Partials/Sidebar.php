<?php

namespace App\Livewire\Partials;

use App\Models\Menu;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Isolate;

#[Isolate]
class Sidebar extends Component
{
    public $menus = [];

    public string $searchMenu = '';

    public function mount()
    {

        $id_user = auth()->id();
        $this->getMenuUser(
            id_user: $id_user
        );
    }

    public function getMenuUser(int $id_user)
    {
        $keyCache = 'user-sidebar-menu:' . $id_user;

        $menuCache =  cache()->remember($keyCache, 60 * 60, function () use ($id_user) {
            $menus = $this->getMenuSubmenus();

            if (Auth::user()->hasRole('Super-Admin')) {
                return $menus;
            }

            // Pre-compute semua view permissions user
            $userViewPermissions = $this->getCachedUserViewPermissions(userId: $id_user);

            return collect($menus)
                ->map(function ($groupedMenus, $groupName) use ($userViewPermissions) {
                    $filteredMenus = collect($groupedMenus)
                        ->map(function ($menu) use ($userViewPermissions) {
                            return $this->filterMenuWithViewPermissions($menu, $userViewPermissions);
                        })
                        ->filter()
                        ->values()
                        ->toArray();

                    return count($filteredMenus) > 0 ? [$groupName => $filteredMenus] : null;
                })
                ->filter()
                ->collapse()
                ->toArray();
        });

        $this->menus = array_merge($this->menus, $menuCache);

        return $this->menus; // Return the merged menus
    }

    /**
     * Hanya ambil permissions yang diawali dengan 'view'
     */
    private function getCachedUserViewPermissions(int $userId)
    {
        $permissionCacheKey = 'user-permissions:view:' . $userId;

        return cache()->remember($permissionCacheKey, 60 * 60, function () {
            $user = Auth::user();

            $allPermissions = method_exists($user, 'getAllPermissions')
                ? $user->getAllPermissions()->pluck('name')->toArray()
                : ($user->permissions->pluck('name')->toArray() ?? []);

            // Filter hanya permissions yang diawali dengan 'view'
            return array_filter($allPermissions, function ($permission) {
                return str_starts_with($permission, 'view');
            });
        });
    }

    /**
     * Filter menu menggunakan pre-computed view permissions
     */
    private function filterMenuWithViewPermissions($menu, array $userViewPermissions)
    {
        // Check menu permissions
        // dd($menu, $userViewPermissions);

        $menuPermissions = !empty($menu['permission']) ? $menu['permission'] : [];
        // dd($menuPermissions);
        $hasParentPermission = !empty(array_intersect($menuPermissions, $userViewPermissions));

        // Filter submenus
        $permittedSubmenus = collect($menu['submenus'] ?? [])
            ->filter(function ($submenu) use ($userViewPermissions) {
                $submenuPermissions = !empty($submenu['permission']) ? $submenu['permission'] : [];
                return !empty(array_intersect($submenuPermissions, $userViewPermissions));
            })
            ->values()
            ->toArray();

        if ($hasParentPermission || count($permittedSubmenus) > 0) {
            $menu['submenus'] = $permittedSubmenus;
            return $menu;
        }

        return null;
    }


    public function updatedSearchMenu()
    {
        // Update the menus when searchMenu changes
        $this->menus = $this->getMenuSubmenus();
    }

    function getMenuSubmenus()
    {
        $cacheKey = "user-sidebar-menu:menus";
        $cacheExp = 60 * 720; //12 jam

        return cache()->remember($cacheKey, $cacheExp, function () {
            $MainMenu = Menu::first();
            $menus = Menu::where('parent_id', $MainMenu->id) // select menu yg bukan submenu, parent_id = 0
                ->with(['submenus' => function ($query) {
                    // Filter submenus if search is applied
                    if ($this->searchMenu) {
                        $query->where('nama', 'like', '%' . $this->searchMenu . '%');
                    }
                }])
                ->when(
                    $this->searchMenu,
                    function ($query) {
                        $query->where('nama', 'like', '%' . $this->searchMenu . '%')
                            ->orWhereHas('submenus', function ($subQuery) {
                                $subQuery->where('nama', 'like', '%' . $this->searchMenu . '%');
                            });
                    }
                )
                ->orderBy('group')
                ->orderBy('nama')
                ->get()
                ->map(function ($menu) {
                    return [ //mapping menu utama
                        'id' => $menu->id,
                        'nama' => $menu->nama,
                        'route' => $menu->route ?? '',
                        'icon' => $menu->icon ?? '',
                        'permission' => $menu->permission ?? '',
                        'group' => $menu->group ? $menu->group->nama() : '',
                        'submenus' => $menu->submenus
                            ->sortBy('nama') //sort submenu
                            ->map(
                                function ($submenu) {
                                    return [ //mapping submenu
                                        'id' => $submenu->id,
                                        'nama' => $submenu->nama,
                                        'route' => $submenu->route ?? '',
                                        'icon' => $submenu->icon ?? '',
                                        'permission' => $submenu->permission ?? '',
                                        'group' => $submenu->group ? $submenu->group->nama() : '',
                                    ];
                                }
                            )->toArray(),
                    ];
                })
                ->groupBy('group')
                ->toArray();

            return $menus;
        });
    }

    public function render()
    {
        return view('livewire.partials.sidebar');
    }
}
