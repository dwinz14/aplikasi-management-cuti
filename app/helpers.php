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
            // Check roles for parent menu
            if (in_array($userRole, $item['roles'])) {
                // If has children, filter children by role as well
                if (isset($item['children'])) {
                    $filteredChildren = [];
                    foreach ($item['children'] as $child) {
                        if (in_array($userRole, $child['roles'])) {
                            $filteredChildren[] = $child;
                        }
                    }
                    if (!empty($filteredChildren)) {
                        $item['children'] = $filteredChildren;
                        $filteredItems[] = $item;
                    }
                } else {
                    $filteredItems[] = $item;
                }
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
