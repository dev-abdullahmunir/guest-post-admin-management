<?php

namespace Modules\AdminManagement\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\AdminManagement\Models\Setting;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            // Branding Group
            ['key' => 'app_name', 'value' => 'GuestPost Pro', 'group' => 'branding'],
            ['key' => 'app_tagline', 'value' => 'Publish • Grow • Rank', 'group' => 'branding'],
            ['key' => 'app_logo', 'value' => '', 'group' => 'branding'],
            ['key' => 'app_icon', 'value' => 'fa-solid fa-chart-line', 'group' => 'branding'],
            
            // Business & Contact Group
            ['key' => 'company_name', 'value' => 'GuestPost Pro Agency', 'group' => 'contact'],
            ['key' => 'company_email', 'value' => 'support@guestpostpro.com', 'group' => 'contact'],
            ['key' => 'company_phone', 'value' => '+1 (555) 234-5678', 'group' => 'contact'],
            ['key' => 'company_address', 'value' => '100 Innovation Way, Suite 400, New York, NY 10001', 'group' => 'contact'],
            ['key' => 'footer_copyright', 'value' => '© 2026 GuestPost Pro. All rights reserved.', 'group' => 'contact'],

            // Localization & Currency Group
            ['key' => 'currency_symbol', 'value' => '$', 'group' => 'localization'],
            ['key' => 'currency_code', 'value' => 'USD', 'group' => 'localization'],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'group' => 'localization'],
            ['key' => 'timezone', 'value' => 'UTC', 'group' => 'localization'],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
