<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'project_name'  => 'Ktorze M',
            'contact_email'  => 'contato@ktorzem.com',
            'contact_phone'  => '719999999999',
            'contact_address' => 'Av. Principal, Buraquinho, Lauro de Freitas',
            'facebook_url'   => 'https://facebook.com',
            'instagram_url'  => 'https://instagram.com/ktorzem',
            'logo'           => '/logo-ktorze.png',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
