<?php

namespace App\Livewire\Profile;

use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class RolePermission extends Component
{
    public function render()
    {
        return view('livewire.profile.role-permission');
    }
}
