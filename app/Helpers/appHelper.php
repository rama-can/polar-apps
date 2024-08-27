<?php

use App\Models\Navigation;
use App\Models\Role;

if (!function_exists('getMenus')) {
    function getMenus()
    {
        return Navigation::with('subMenus')->orderBy('sort', 'desc')->get();
    }
}

if (!function_exists('getParentMenus')) {
    function getParentMenus($url)
    {
        // $menu = Navigation::where('url', $url)->first();
        $menu = Navigation::where('url', 'LIKE', '%' . $url . '%')->first();
        if ($menu) {
            $parentMenu = Navigation::select('name')->where('id', $menu->main_menu)->first();
            return $parentMenu->name ?? '';
        }
        return '';
    }
}

if (!function_exists('getRoles')) {
    function getRoles()
    {
        if (auth()->user()->hasRole('administrator')) {
            return Role::all();
        } else {
            return Role::where('name', '!=', 'administrator')->get();
        }
    }
}
