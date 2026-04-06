<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;

class CmsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::allKeyed();
        return view('dashboard.cms', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        SiteSetting::setMany($data);
        return back()->with('success', app()->getLocale() === 'ar' ? 'تم حفظ الإعدادات بنجاح' : 'Settings saved successfully');
    }
}
