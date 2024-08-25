<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserRolePermissionSeeder::class,
            NavigationSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            SettingSeeder::class,
            // WorkInstructionSeeder::class,
            // UsageLogbookSeeder::class,
            // CalibrationLogbookSeeder::class,
        ]);

        //\App\Models\UsageLogbook::factory(100)->create();
        //\App\Models\CalibrationLogbook::factory(100)->create();
    }
}
