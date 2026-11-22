<?php

namespace Modules\AdminManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shared\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Render the Settings Management Blade view.
     */
    public function renderView($viewFactory)
    {
        try {
            $settings = Setting::getAllKeyValues();
        } catch (\Exception $e) {
            $settings = [];
        }

        // Fallbacks for default settings
        $defaults = [
            'app_name' => 'GuestPost Pro',
            'app_tagline' => 'Publish • Grow • Rank',
            'app_logo' => '',
            'app_icon' => 'fa-solid fa-chart-line',
            'company_name' => 'GuestPost Pro Agency',
            'company_email' => 'support@guestpostpro.com',
            'company_phone' => '+1 (555) 234-5678',
            'company_address' => '100 Innovation Way, Suite 400, New York, NY 10001',
            'footer_copyright' => '© 2026 GuestPost Pro. All rights reserved.',
            'currency_symbol' => '$',
            'currency_code' => 'USD',
            'date_format' => 'Y-m-d',
            'timezone' => 'UTC',
        ];

        $settings = array_merge($defaults, $settings);

        return $viewFactory->make('settings.index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Get settings as JSON.
     */
    public function index(Request $request): JsonResponse
    {
        $settings = Setting::getAllKeyValues();
        return response()->json([
            'status' => 'success',
            'data' => $settings,
        ]);
    }

    /**
     * Update settings in batch.
     */
    public function update(array $body): array
    {
        // Groups mapping for automatic categorisation
        $groupMap = [
            'app_name' => 'branding',
            'app_tagline' => 'branding',
            'app_logo' => 'branding',
            'app_icon' => 'branding',
            'company_name' => 'contact',
            'company_email' => 'contact',
            'company_phone' => 'contact',
            'company_address' => 'contact',
            'footer_copyright' => 'contact',
            'currency_symbol' => 'localization',
            'currency_code' => 'localization',
            'date_format' => 'localization',
            'timezone' => 'localization',
        ];

        foreach ($body as $key => $value) {
            $cleanKey = trim($key);
            if ($cleanKey === 'id' || $cleanKey === '_token') continue;
            
            $group = $groupMap[$cleanKey] ?? 'general';
            Setting::set($cleanKey, is_string($value) ? trim($value) : $value, $group);
        }

        return [
            'status' => 'success',
            'message' => 'System settings and branding updated successfully!',
            'data' => Setting::getAllKeyValues()
        ];
    }
}
