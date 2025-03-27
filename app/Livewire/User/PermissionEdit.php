<?php

namespace App\Livewire\User;

use App\Models\Menu;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use TallStackUi\Traits\Interactions;

#[Lazy]
class PermissionEdit extends Component
{
    use Interactions;

    public ?User $user;
    public $mainMenu;
    public $menus;
    // public $menu;


    public $permission = [];
    public $rolePermission = [];

    // public $rules = [
    //     'permission' => 'required'
    // ];

    public function mount($id)
    {
        $this->user = User::findOrFail($id);
        $this->mainMenu = Menu::first();

        $this->menus = Menu::where('id', '!=', $this->mainMenu->id)->with('submenus')->get();
        $this->permission = $this->user->getAllPermissions()->pluck('name')->toArray();


        $roleUser = Role::findByName($this->user->getRoleNames()[0]);
        $this->rolePermission = $roleUser->permissions->pluck('name');
    }

    function submit()
    {
        #Spesial permssion to user, selain user mempunyai permission berdasarkan Role, bisa juga diberi permission khusus dari sini

        // $this->validate();

        DB::beginTransaction();
        try {
            $this->user->syncPermissions($this->permission);

            DB::commit();

            $this->dispatch('updated-permission-user');
            $this->toast()
                ->success('Sukses', 'Spesial permission diperbaharui.')
                ->send();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->toast()
                ->error('Failed', 'Error: ' . $e->getMessage())
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.user.permission-edit');
    }
}
