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

    public function mount(): void
    {
        $this->loadMenus();
    }

    public function updatedSearchMenu(): void
    {
        // saat pencarian
        $this->loadMenus();
    }

    /**
     * Main entry point: load + filter + search menus into $this->menus
     */
    private function loadMenus(): void
    {
        $allMenus = $this->getPermittedMenus(auth()->id());
        $this->menus = $this->applySearchFilter($allMenus);
    }

    /**
     * Get full menu tree filtered by user permissions (cached per user)
     */
    private function getPermittedMenus(int $userId): array
    {
        $cacheKey = 'user-sidebar-menu:' . $userId;

        return cache()->remember($cacheKey, 60 * 60, function () use ($userId) {
            $allMenus = $this->getCachedBaseMenus();

            if (Auth::user()->hasRole('Super-Admin')) {
                return $allMenus;
            }

            $userViewPermissions = $this->getCachedUserViewPermissions($userId);

            return collect($allMenus)
                ->map(function ($groupedMenus, $groupName) use ($userViewPermissions) {
                    $filtered = collect($groupedMenus)
                        ->map(fn($menu) => $this->filterMenuWithViewPermissions($menu, $userViewPermissions))
                        ->filter()
                        ->values()
                        ->toArray();

                    return count($filtered) > 0 ? [$groupName => $filtered] : null;
                })
                ->filter()
                ->collapse()
                ->toArray();
        });
    }

    /**
     * Base menu structure — cached globally (no user/search context)
     */
    private function getCachedBaseMenus(): array
    {
        return cache()->remember('user-sidebar-menu:base', 60 * 720, function () {
            $mainMenu = Menu::first();

            return Menu::where('parent_id', $mainMenu->id)
                ->with('submenus')
                ->orderBy('group')
                ->orderBy('nama')
                ->get()
                ->map(fn($menu) => [
                    'id'         => $menu->id,
                    'nama'       => $menu->nama,
                    'route'      => $menu->route ?? '',
                    'icon'       => $menu->icon ?? '',
                    'permission' => $menu->permission ?? '',
                    'group'      => $menu->group?->nama() ?? '',
                    'submenus'   => $menu->submenus
                        ->sortBy('nama')
                        ->map(fn($sub) => [
                            'id'         => $sub->id,
                            'nama'       => $sub->nama,
                            'route'      => $sub->route ?? '',
                            'icon'       => $sub->icon ?? '',
                            'permission' => $sub->permission ?? '',
                            'group'      => $sub->group?->nama() ?? '',
                        ])->values()->toArray(),
                ])
                ->groupBy('group')
                ->toArray();
        });
    }

    /**
     * Apply search filter in-memory on already-permitted menus
     */
    private function applySearchFilter(array $menus): array
    {
        if (empty($this->searchMenu)) {
            return $menus;
        }

        $search = strtolower($this->searchMenu);
        $result = [];

        foreach ($menus as $group => $groupMenus) {
            $matched = array_values(array_filter(
                array_map(function ($menu) use ($search) {
                    $menuMatches = str_contains(strtolower($menu['nama']), $search);

                    $menu['submenus'] = array_values(array_filter(
                        $menu['submenus'],
                        fn($sub) => str_contains(strtolower($sub['nama']), $search)
                    ));

                    // Include menu if its name matches OR it has matching submenus
                    return ($menuMatches || !empty($menu['submenus'])) ? $menu : null;
                }, $groupMenus)
            ));

            if (!empty($matched)) {
                $result[$group] = $matched;
            }
        }

        return $result;
    }

    /**
     * Get only 'view' permissions for a user (cached per user)
     */
    private function getCachedUserViewPermissions(int $userId): array
    {
        return cache()->remember('user-permissions:view:' . $userId, 60 * 60, function () {
            $user = Auth::user();

            $all = method_exists($user, 'getAllPermissions')
                ? $user->getAllPermissions()->pluck('name')->toArray()
                : $user->permissions->pluck('name')->toArray();

<<<<<<< HEAD
            return array_values(array_filter($all, fn($p) => str_starts_with($p, 'view')));
=======
            $permissions = array_values(array_filter($all, fn($p) => str_starts_with($p, 'view')));

            // Tambahkan permission view koordinator jika user adalah koordinator
            if ($user && $user->isKoordinator()) {
                $permissions = array_merge($permissions, [
                    'view-kepegawaian-jadwal-kerja',
                    'view-kepegawaian-konfigurasi-jadwal',
                ]);
            }

            // Filter ketersediaan menu Jadwal Kerja sesuai wewenang user
            if ($user && $user->can('view-kepegawaian-jadwal-kerja')) {
                if (!in_array('view-kepegawaian-jadwal-kerja', $permissions)) {
                    $permissions[] = 'view-kepegawaian-jadwal-kerja';
                }
            } else {
                $permissions = array_values(array_filter($permissions, fn($p) => $p !== 'view-kepegawaian-jadwal-kerja'));
            }

            // Allow users with assigned ruangan to view the asset & pengajuan menu
            if ($user?->karyawan?->ruangan_id) {
                if (!in_array('view-umum-asset', $permissions)) {
                    $permissions[] = 'view-umum-asset';
                }
                if (!in_array('view-umum-pengajuan', $permissions)) {
                    $permissions[] = 'view-umum-pengajuan';
                }
            }

            return $permissions;
>>>>>>> 57adf2c (fix(auth): update absensi control authorization to use Spatie view-kepegawaian-absensi permission)
        });
    }

    /**
     * Filter a single menu item and its submenus by view permissions
     */
    private function filterMenuWithViewPermissions(array $menu, array $userViewPermissions): ?array
    {
        $menuPermissions    = !empty($menu['permission']) ? $menu['permission'] : [];
        $hasParentPermission = !empty(array_intersect($menuPermissions, $userViewPermissions));

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

    public function render()
    {
        return view('livewire.partials.sidebar');
    }
}
