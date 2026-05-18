<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class PublicSettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $keys = [
            'platform_policy_ar', 'platform_policy_en',
            'support_ar', 'support_en',
        ];

        $settings = SiteSetting::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        return response()->json($settings);
    }
}
