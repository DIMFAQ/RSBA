<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('components.layouts.app')]
class DisplayMonitorAdmin extends Component
{
    // Tabs: dashboard | devices | data | logs | inpatient_rooms
    public string $activeTab = 'dashboard';

    // Success/error alerts
    public string $successMessage = '';
    public string $errorMessage = '';

    public function render()
    {
        return view('livewire.dashboard.display-monitor-admin')->title('Display Monitor Admin Panel');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetErrorBag();
        $this->successMessage = '';
        $this->errorMessage   = '';
    }

    #[On('notify-success')]
    public function handleNotifySuccess(string $message): void
    {
        $this->successMessage = $message;
        $this->errorMessage = '';
    }

    #[On('notify-error')]
    public function handleNotifyError(string $message): void
    {
        $this->errorMessage = $message;
        $this->successMessage = '';
    }
}
