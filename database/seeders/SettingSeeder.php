<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'site_title' => 'Polar',
            'site_description' => 'Portal Layanan Laboratorium Berbasis Mobile',
            'site_logo' => 'logo.png',
            'site_favicon' => 'favicon.png',
            'site_address' => 'Jl. Raya Jemursari No. 244, Surabaya',
            'site_email' => '',
            'is_register' => false,
            'is_forgot_password' => false,
            'contact_phone' => '',
            'default_language' => 'en',
        ];

        foreach ($settings as $key => $value) {
            settings()->set($key, $value);
        }
    }
}
