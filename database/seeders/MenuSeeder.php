<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'nama' => 'Main Menu',
                'route' => null,
                'icon' => null,
                'permission' => null,
                'group' => null,
                'submenus' => [
                    [
                        'nama' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => 'view-dashboard',
                        'group' => null,
                        'submenus' => []
                    ],
                    [
                        'nama' => 'Dashboard Kamar',
                        'route' => 'dashboard.kamar',
                        'icon' => 'home',
                        'permission' => 'view-dashboard-kamar',
                        'group' => null,
                        'submenus' => []
                    ],
                    [
                        'nama' => 'User',
                        'route' => 'admin.user.index',
                        'icon' => 'users',
                        'permission' => 'view-admin-user',
                        'group' => 'adm',
                        'submenus' => []
                    ],
                    [
                        'nama' => 'Settings',
                        'route' => null,
                        'icon' => 'settings',
                        'permission' => 'view-settings',
                        'group' => 'adm',
                        'submenus' => [
                            [
                                'nama' => 'Menu',
                                'route' => 'admin.settings.menu',
                                'icon' => null,
                                'permission' => 'view-admin-settings-menu',
                                'group' => 'adm',
                                'submenus' => []
                            ],
                            [
                                'nama' => 'Perusahaan',
                                'route' => 'admin.settings.perusahaan',
                                'icon' => null,
                                'permission' => 'view-admin-settings-perusahaan',
                                'group' => 'adm',
                                'submenu' => []
                            ]
                        ],

                    ],
                    [
                        'nama' => 'Role Permission',
                        'route' => null,
                        'icon' => 'circle-key',
                        'permission' => 'view-role-permission',
                        'group' => 'adm',
                        'submenus' => [
                            [
                                'nama' => 'Role',
                                'route' => 'admin.settings.role',
                                'icon' => null,
                                'permission' => 'view-admin-settings-role',
                                'group' => 'adm',
                                'submenus' => []
                            ],
                            [
                                'nama' => 'Permission',
                                'route' => 'admin.settings.permission',
                                'icon' => null,
                                'permission' => 'view-admin-settings-permission',
                                'group' => 'adm',
                                'submenus' => []
                            ]
                        ],

                    ],
                ]
            ],

        ];

        // Process menus recursively
        foreach ($menus as $menuData) {
            $this->createMenu($menuData);
        }
    }


    private function createMenu(array $menuData, ?int $parentId = null): void
    {
        // DB::beginTransaction();
        try {

            if (!empty($menuData['permission'])) {
                Permission::firstOrCreate(['name' => $menuData['permission']]);
                $collectionPermission[] = $menuData['permission'];
            }

            $menu = Menu::create([
                'nama' => $menuData['nama'],
                'route' => $menuData['route'] ?? null,
                'icon' => $menuData['icon'] ?? null,
                'permission' => !empty($collectionPermission) ? $collectionPermission : null,
                'group' => $menuData['group'] ?? null,
                'parent_id' => $parentId,
            ]);
        } catch (\Throwable $e) {
            throw $e;
        }

        if (!empty($menuData['submenus'])) {
            foreach ($menuData['submenus'] as $submenuData) {
                $this->createMenu($submenuData, $menu->id);
            }
        }
    }
}
