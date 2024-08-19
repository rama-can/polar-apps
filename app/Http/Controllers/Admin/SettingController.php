<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read settings')->only(['index', 'update']);
    }

    public function index()
    {
        return view('admin.setting.index', [
            'title' => 'Application Settings',
        ]);
    }

    public function update(Request $request)
    {
        $settings = array_map(function($key) {
            return trim($key, "'");
        }, array_keys($request->input('settings')));

        $values = array_values($request->input('settings'));
        $settings = array_combine($settings, $values);

        // Validasi setelah pembersihan
        $validatedData = $request->validate([
            'settings.*' => 'required|string',
        ]);

        foreach ($settings as $key => $value) {
            settings()->set($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated');
    }
}
