<?php

namespace App\Traits;

use Exception;

trait AuthorizesFromRoute
{
    public string $currentRouteName = '';

    public function bootAuthorizesFromRoute(): void
    {

        if (!empty($this->currentRouteName)) return;

        // Ambil dari Referer header — selalu URL halaman asli
        $referer = request()->header('referer');

        if (!$referer) return;

        try {
            $request   = request()->create($referer);
            $route     = app('router')->getRoutes()->match($request);
            $routeName = $route->getName();

            if ($routeName && $routeName !== 'livewire.update') {
                $this->currentRouteName = $routeName;
            }
        } catch (Exception) {
            // route tidak ditemukan
        }
    }

    protected function buildPermission(): string
    {
        $segments = explode('.', $this->currentRouteName);
        $last     = end($segments);

        $actions = ['index', 'show', 'create', 'edit', 'delete'];

        // Jika segmen terakhir adalah action, buang dari resource
        if (in_array($last, $actions)) {
            array_pop($segments);
        }

        $resource = implode('-', $segments);
        $action = $this->getActionFromComponent();

        // dd("{$action}-{$resource}");
        return "{$action}-{$resource}";
    }

    protected function getActionFromComponent(): string
    {
        $className = strtolower(class_basename(static::class));

        return match ($className) {
            'index', 'show' => 'view',
            'add'         => 'add',
            'edit'        => 'edit',
            'delete'      => 'delete',
            default       => $className
        };
    }

    protected function authorizeFromRoute(): void
    {
        $permission = $this->buildPermission();

<<<<<<< HEAD
=======
        // Bypassing permission check untuk Koordinator Ruangan pada menu utama kepegawaian
        if (auth()->user()?->isKoordinator() && in_array($permission, [
            'view-kepegawaian-jadwal-kerja',
            'view-kepegawaian-konfigurasi-jadwal',
        ])) {
            return;
        }

>>>>>>> 57adf2c (fix(auth): update absensi control authorization to use Spatie view-kepegawaian-absensi permission)
        abort_unless(
            auth()->user()?->can($permission),
            403,
            "Tidak memiliki akses: {$permission}"
        );
    }
}
