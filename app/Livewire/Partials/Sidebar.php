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

    function mount()
    {
        $menus = $this->getMenuSubmenus();
        /**
         * Main menu & submenu di filter berdasarkan permission user 
         */
        if (!Auth::user()->hasRole('Super-Admin')) {


            // Not Super-Admin
            // $menus = collect($menus)->map(function ($menu) {
            //     // Check if the user has 'view-*' permission for the parent menu
            //     $hasParentPermission = collect($menu['permission'])->contains(
            //         fn($permission) => str_starts_with($permission, 'view') && Auth::user()->hasPermissionTo($permission)
            //     );

            //     // Filter submenus to include only those with permissions
            //     $permittedSubmenus = collect($menu['submenus'])->filter(function ($submenu) {
            //         return collect($submenu['permission'])->contains(
            //             fn($permission) => str_starts_with($permission, 'view') && Auth::user()->hasPermissionTo($permission)
            //         );
            //     })->values()->toArray();

            //     // Include the menu if the user has permission for the parent or any of its submenus
            //     if ($hasParentPermission || count($permittedSubmenus) > 0) {
            //         $menu['submenus'] = $permittedSubmenus; // Set only the permitted submenus
            //         return $menu;
            //     }

            //     return null; // Exclude this menu if no permissions
            // })->filter()->toArray();



            // BUKAN ADMIN
            // New With Group
            $menus = collect($menus) //Mengubah array `$menus` menjadi koleksi untuk memanfaatkan fungsi-fungsi Laravel Collection

                //[01] each menu pada group , eksekusi per group menu
                ->map(function ($groupedMenus, $groupName) {

                    // Proses by setiap menu dalam group
                    $filteredMenus = collect($groupedMenus)->map(function ($menu) {

                        // [02] Check jika user has 'view-*' permission pada menu utama
                        // **Pastikan key 'permission' ada dan tidak kosong
                        $hasParentPermission = !empty($menu['permission'])
                            &&
                            collect($menu['permission'])->contains(
                                fn($permission) => str_starts_with($permission, 'view') && Auth::user()->hasPermissionTo($permission)
                            );

                        // [03] Filter submenus jika subemenu adaa item nya
                        $permittedSubmenus = collect($menu['submenus'] ?? [])->filter(function ($submenu) {
                            // Periksa izin untuk setiap submenu
                            // ** Pastikan key 'permission' pada submenu ada dan tidak kosong
                            return !empty($submenu['permission'])
                                &&
                                collect($submenu['permission'])->contains(
                                    fn($permission) => str_starts_with($permission, 'view') && Auth::user()->hasPermissionTo($permission)
                                );

                            // Mengatur hasil filter submenu menjadi array yang rapi
                        })->values()->toArray();

                        // [04] return ke group
                        // Sertakan menu induk jika user memiliki izin atau submenunya ada yang diizinkan
                        if ($hasParentPermission || count($permittedSubmenus) > 0) {
                            $menu['submenus'] = $permittedSubmenus; // Menyimpan submenu yang diizinkan saja
                            return $menu; // Mengembalikan menu dengan submenu yang sudah difilter
                        }


                        // EXCLUDE  
                        //Jika tidak memiliki izin, menu ini tidak disertakan
                        return null;

                        // Menghapus menu yang `null` dan menyusun ulang indeks array
                    })->filter()->values()->toArray();

                    // Sertakan grup jika masih memiliki menu setelah difilter
                    return count($filteredMenus) > 0 ? [$groupName => $filteredMenus] : null;
                })
                ->filter() // Menghapus grup menu yang kosong
                ->collapse() // Menggabungkan hasil menjadi struktur array satu tingkat
                ->toArray(); // Mengubah kembali menjadi array biasa
        }

        // merge ke menus
        $this->menus = array_merge($this->menus, $menus);
    }


    public function updatedSearchMenu()
    {
        // Update the menus when searchMenu changes
        $this->menus = $this->getMenuSubmenus();
    }


    function getMenuSubmenus()
    {
        return cache()->remember('menus', 60 * 60, function () {
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
