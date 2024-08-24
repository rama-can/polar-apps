<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $users = $this->createUsers();

            $this->createUserProfile();

            $this->createRolesAndPermissions($users);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th; // This will help you identify the error if something goes wrong
        }
    }

    public function createRolesAndPermissions($users)
    {
        // Membuat role
        $role_admin = Role::firstOrCreate(['name' => 'administrator', 'guard_name' => 'web']);
        $role_staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $role_student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // Daftar izin
        $permissions = ['read', 'create', 'update', 'delete'];
        $modulesWithFullPermissions = ['configurations', 'permissions', 'roles', 'navigation', 'users', 'product-categories', 'products', 'product-images', 'work-instructions', 'usage-logbooks', 'calibration-logbooks', 'front-work-instructions', 'front-usage-logbooks', 'front-calibration-logbooks'];
        $readOnlyModules = ['dashboard', 'qrcodes', 'exports', 'settings'];

        // Memberikan izin penuh untuk module-module tertentu
        foreach ($modulesWithFullPermissions as $module) {
            foreach ($permissions as $permission) {
                $permissionName = "{$permission} {$module}";
                $permissionInstance = Permission::firstOrCreate(['name' => $permissionName]);

                // Memberikan izin kepada role admin
                $role_admin->givePermissionTo($permissionInstance);
            }
        }

        // Memberikan izin read-only untuk module tertentu
        foreach ($readOnlyModules as $module) {
            $permissionName = "read {$module}";
            $permissionInstance = Permission::firstOrCreate(['name' => $permissionName]);

            // Memberikan izin kepada role admin
            $role_admin->givePermissionTo($permissionInstance);
        }

        // Memberikan permission front ke role staff dan student
        $frontModulesStaff = ['front-work-instructions', 'front-usage-logbooks', 'front-calibration-logbooks'];
        foreach ($frontModulesStaff as $module) {
            foreach ($permissions as $action) {
                $permission = Permission::where('name', "$action $module")->first();
                if ($permission) {
                    $role_staff->givePermissionTo($permission);
                }
            }
        }

        // memberikan permission read dan create ke role student
        $readPermissionsStudent = ['front-work-instructions', 'front-calibration-logbooks'];
        foreach ($readPermissionsStudent as $module) {
            foreach ($permissions as $action) {
                $permission = Permission::where('name', 'like', 'read ' . "$module")->first();
                if ($permission) {
                    $role_student->givePermissionTo($permission);
                }
            }
        }

        $frontModulesStudent = ['front-usage-logbooks'];

        foreach ($frontModulesStudent as $module) {
            $readPermission = Permission::where('name', 'like', 'read ' . $module)->first();
            $createPermission = Permission::where('name', 'like', 'create ' . $module)->first();

            if ($readPermission) {
                $role_student->givePermissionTo($readPermission);
            }

            if ($createPermission) {
                $role_student->givePermissionTo($createPermission);
            }
        }

        // Menugaskan role ke pengguna
        if (isset($users['administrator'])) {
            $users['administrator']->assignRole($role_admin);
        }
        if (isset($users['administrator_2'])) {
            $users['administrator_2']->assignRole($role_admin);
        }
        if (isset($users['staff'])) {
            $users['staff']->assignRole($role_staff);
        }
        if (isset($users['student'])) {
            $users['student']->assignRole($role_student);
        }
    }

    public function createUsers()
    {
        $result = [];

        $result['administrator'] = User::create([
            'name' => 'Rama Can',
            'email' => 'superadmin@mail.ru',
            'username' => 'superadmin',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => 1,
        ]);

        $result['administrator_2'] = User::create([
            'name' => 'Administrator',
            'email' => 'polaradmin@mail.ru',
            'username' => 'polaradmin',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => 1,
        ]);

        $result['staff'] = User::create([
            'name' => 'Staff',
            'email' => 'polarstaff@mail.ru',
            'username' => 'polarstaff',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => 1,
        ]);

        $result['student'] = User::create([
            'name' => 'Student',
            'email' => 'polarstudent@mail.ru',
            'username' => 'polarstudent',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => 1,
        ]);

        return $result;
    }

    public function createUserProfile()
    {
        $admin = User::where('email', 'superadmin@mail.ru')->first();
        if ($admin) {
            $admin->profile()->create([
                'phone_number' => '089678468651',
                'place_birth' => 'Jakarta',
                'date_birth' => '1991-04-05',
                'gender' => 'laki-laki',
                'address' => 'Jl. H. Gadung no.20, Pondok Ranji, Ciputat Timur, Tangerang Selatan, Banten',
            ]);
        }

        $admin2 = User::where('email', 'polaradmin@mail.ru')->first();
        if ($admin2) {
            $admin2->profile()->create([
                'phone_number' => '089678468652',
                'place_birth' => 'Jakarta',
                'date_birth' => '1992-04-05',
                'gender' => 'laki-laki',
                'address' => 'Jl. H. Gadung no.21, Pondok Ranji, Ciputat Timur, Tangerang Selatan, Banten',
            ]);
        }

        $staff = User::where('email', 'polarstaff@mail.ru')->first();
        if ($staff) {
            $staff->profile()->create([
                'phone_number' => '08123456799',
                'place_birth' => 'Bogor',
                'date_birth' => '1994-01-01',
                'gender' => 'laki-laki',
                'address' => 'Jalan Bogor Raya No. 19',
            ]);
        }

        $student = User::where('email', 'polarstudent@mail.ru')->first();
        if ($student) {
            $student->profile()->create([
                'phone_number' => '08123456789',
                'place_birth' => 'Bandung',
                'date_birth' => '1990-11-21',
                'gender' => 'laki-laki',
                'address' => 'Jalan Bandung Utara No. 123',
            ]);
        }
    }
}
