<?php

namespace Database\Seeders;

use App\Models\Navigation;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataNavigations = [
            [
                'name' => 'Configurations',
                'url' => '#',
                'permission' => 'configurations',
                'icon' => 'ti-settings',
                'main_menu' => null,
                'type_menu' => 'parent',
            ],
            [
                'name' => 'App Settings',
                'url' => 'admin/application-settings',
                'permission' => 'settings',
                'icon' => '',
                'main_menu' => 1,
                'type_menu' => 'child',
            ],
            [
                'name' => 'Export',
                'url' => 'admin/exports',
                'permission' => 'exports',
                'icon' => '',
                'main_menu' => 1,
                'type_menu' => 'child',
            ],
            [
                'name' => 'Roles',
                'url' => 'admin/roles',
                'permission' => 'roles',
                'icon' => '',
                'main_menu' => 1,
                'type_menu' => 'child',
            ],
            [
                'name' => 'Permissions',
                'url' => 'admin/permissions',
                'permission' => 'permissions',
                'icon' => '',
                'main_menu' => 1,
                'type_menu' => 'child',
            ],
            [
                'name' => 'Navigation',
                'url' => 'admin/navigations',
                'permission' => 'navigations',
                'icon' => '',
                'main_menu' => 1,
                'type_menu' => 'child',
            ],
            [
                'name' => 'Users',
                'url' => 'admin/users',
                'permission' => 'users',
                'icon' => 'fas fa-users',
                'main_menu' => null,
                'sort' => 1,
                'type_menu' => 'single',
            ],
            [
                'name' => 'Product',
                'url' => 'admin/products',
                'permission' => 'products',
                'icon' => 'fas fa-book',
                'main_menu' => null,
                'sort' => 3,
                'type_menu' => 'single',
            ],
            [
                'name' => 'Categories',
                'url' => 'admin/product-categories',
                'permission' => 'product-categories',
                'icon' => 'fas fa-list-alt',
                'main_menu' => null,
                'sort' => 2,
                'type_menu' => 'single',
            ],
        ];

        foreach ($dataNavigations as $dataNavigation) {
            Navigation::create($dataNavigation);
        }

        $adminRole = Role::firstOrCreate(['name' => 'administrator']);

        // Mendapatkan ID peran "admin"
        $adminRoleId = $adminRole->id;

        // Mendapatkan semua navigasi
        $navigations = Navigation::all();

        // Melampirkan peran "admin" ke setiap navigasi
        foreach ($navigations as $navigation) {
            $navigation->roles()->syncWithoutDetaching([$adminRoleId]);
        }
    }
}
