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
        $roles = Role::all();
        $settings = CommunicationSetting::all()->keyBy('role_id');

        return view('admin.communication_settings.index', compact('roles', 'settings'));
    }

    public function update(Request $request)
    {
        $settingsInput = $request->input('settings', []);

        foreach ($settingsInput as $roleId => $channels) {
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
