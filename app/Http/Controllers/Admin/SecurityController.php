<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DevtoolsViolation;
use App\Models\SecuritySetting;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index()
    {
        $violations = DevtoolsViolation::with('user:id,name,email')
            ->orderByDesc('created_at')
            ->paginate(20);

        $settings = [
            'devtools_log_enabled' => SecuritySetting::getBool('devtools_log_enabled', true),
            'devtools_lock_after_violations' => SecuritySetting::getInt('devtools_lock_after_violations', 1),
            'devtools_violation_window_hours' => SecuritySetting::getInt('devtools_violation_window_hours', 24),
        ];

        return view('admin.security.index', compact('violations', 'settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'devtools_log_enabled' => ['nullable', 'in:0,1'],
            'devtools_lock_after_violations' => ['required', 'integer', 'min:0', 'max:100'],
            'devtools_violation_window_hours' => ['required', 'integer', 'min:1', 'max:720'],
        ]);

        SecuritySetting::set('devtools_log_enabled', (bool) $request->input('devtools_log_enabled', 0));
        SecuritySetting::set('devtools_lock_after_violations', (int) $request->input('devtools_lock_after_violations'));
        SecuritySetting::set('devtools_violation_window_hours', (int) $request->input('devtools_violation_window_hours'));

        return redirect()->route('admin.security.index')
            ->with('success', 'Đã lưu cài đặt bảo mật.');
    }
}
