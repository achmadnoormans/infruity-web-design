<?php
use Illuminate\Support\Facades\Auth;

if (!function_exists('check_access')) {
    function check_access($nama)
    {
        $data = \App\Models\RoleMenu::checkAccess($nama);
        return $data;
    }
}
if (!function_exists('must_access')) {
    function must_access($nama)
    {
        $data = \App\Models\RoleMenu::checkAccess($nama);
        if ($data) {
            return true;
        } else {
            abort(404);
        }
    }
}
function hasPermission(string $permission): bool
{
    $user = Auth::user();
    if (!$user)
        return false;

    // diasumsikan User punya relasi ->permissions (collection)
    return $user->permissions->contains('name', $permission);
}

/**
 * Cek apakah user punya salah satu permission
 */
function hasAnyPermission(array $permissions): bool
{
    foreach ($permissions as $permission) {
        if (hasPermission($permission)) {
            return true;
        }
    }
    return false;
}

/**
 * Generate semua permission dari constant.php
 */
function generatePermissions(): array
{
    $result = [];
    $permissions = config('constant.permissions');

    foreach ($permissions as $key => $permission) {
        foreach ($permission['actions'] as $actionKey => $actionLabel) {
            $result[] = $key . '.' . $actionKey;
        }
    }

    return $result;
}