<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'chatbot_name' => 'nullable|string|max:255',
            'logo_komdigi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_simagang' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_berakhlak' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_banggamelayani' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_antikorupsi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'chatbot_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'chatbot_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->has('site_name')) {
            SystemSetting::set('site_name', $request->site_name);
        }

        if ($request->has('chatbot_name')) {
            SystemSetting::set('chatbot_name', $request->chatbot_name);
        }

        $imageFields = [
            'logo_komdigi',
            'logo_simagang',
            'logo_berakhlak',
            'logo_banggamelayani',
            'logo_antikorupsi',
            'chatbot_icon',
            'chatbot_profile',
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('settings', 'public');
                SystemSetting::set($field, url('storage/' . $path));
            }
        }

        return redirect()->back()->with('success', 'Pengaturan situs berhasil diperbarui.');
    }
}
