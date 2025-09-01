<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('getFilteredMenuItems')) {
    /**
     * Get menu items filtered by user role
     *
     * @return array
     */
    function getFilteredMenuItems()
    {
        $userRole = Auth::user()->role ?? null;

        if (!$userRole) {
            return [];
        }

        $menuConfig = config('menu.items', []);
        $filteredItems = [];

        foreach ($menuConfig as $item) {
            if (in_array($userRole, $item['roles'])) {
                $filteredItems[] = $item;
            }
        }

        return $filteredItems;
    }
}

if (!function_exists('isMenuActive')) {
    /**
     * Check if a menu item is active based on route pattern
     *
     * @param string $pattern
     * @return bool
     */
    function isMenuActive($pattern)
    {
        return request()->routeIs($pattern);
    }
}
