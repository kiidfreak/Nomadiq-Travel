<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Get all public settings (like currency conversion rate)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $settings = [
            'usd_to_ksh_rate' => Setting::getUsdToKshRate(),
        ];

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Get a specific setting value
     * 
     * @param string $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $key)
    {
        $value = Setting::get($key);

        return response()->json([
            'success' => true,
            'data' => [
                'key' => $key,
                'value' => $value,
            ],
        ]);
    }
}

