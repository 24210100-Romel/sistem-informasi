<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings', [
            'fields' => Setting::FIELDS,
            'textarea' => Setting::TEXTAREA,
            'current' => Setting::allKeyed(),
            'aiMode' => config('diskominfo.ai.api_key') ? 'AI (Claude, model '.config('diskominfo.ai.model').')' : 'Pencarian konten (ANTHROPIC_API_KEY belum diisi)',
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate(array_fill_keys(array_keys(Setting::FIELDS), ['nullable', 'string', 'max:2000']) + ['ai_aktif' => ['nullable', 'in:0,1']]);

        foreach (array_keys(Setting::FIELDS) as $key) {
            Setting::updateOrCreate(['k' => $key], ['v' => trim((string) $request->input($key))]);
        }

        return redirect()->route('admin.pengaturan')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
