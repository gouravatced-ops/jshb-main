<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\CommunicationSetting;

class CommunicationSettingController extends Controller
{
    public function index()
    {
        $roles = Role::where('status', 1)->orderBy('id')->get();
        if ($roles->isEmpty()) {
            $roles = Role::orderBy('id')->get();
        }
        $settings = CommunicationSetting::all()->keyBy('role_id');

        $totalRoles = $roles->count();
        $emailEnabledCount = $settings->where('is_email_enabled', 1)->count();
        $smsEnabledCount = $settings->where('is_sms_enabled', 1)->count();
        $whatsappEnabledCount = $settings->where('is_whatsapp_enabled', 1)->count();

        return view('admin.communication_settings.index', compact(
            'roles',
            'settings',
            'totalRoles',
            'emailEnabledCount',
            'smsEnabledCount',
            'whatsappEnabledCount'
        ));
    }

    public function update(Request $request)
    {
        $settingsInput = $request->input('settings', []);
        $allRoles = Role::pluck('id');

        foreach ($allRoles as $roleId) {
            $channels = $settingsInput[$roleId] ?? [];
            CommunicationSetting::updateOrCreate(
                ['role_id' => $roleId],
                [
                    'is_email_enabled' => isset($channels['email']) ? 1 : 0,
                    'is_sms_enabled' => isset($channels['sms']) ? 1 : 0,
                    'is_whatsapp_enabled' => isset($channels['whatsapp']) ? 1 : 0,
                ]
            );
        }

        return redirect()->route('admin.communication-settings.index')
            ->with('success', 'Communication settings updated successfully.');
    }
}
