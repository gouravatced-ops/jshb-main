<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\User;
use App\Models\Division;
use App\Mail\GenericNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::with('creator', 'division', 'targetUser')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        $userTypes = User::select('user_type')->distinct()->whereNotNull('user_type')->pluck('user_type');
        $divisions = Division::where('status', 1)->get();
        $users = User::where('status', 1)->whereNotNull('user_type')->get();
        return view('admin.notices.create', compact('userTypes', 'divisions', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'notice_type' => 'required|in:announcement,warning,new,info',
            'target_user_type' => 'nullable|string',
            'target_division_id' => 'nullable|exists:divisions,id',
            'target_user_id' => 'nullable|array',
            'target_user_id.*' => 'exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $notice = Notice::create([
            'title' => $request->title,
            'message' => $request->message,
            'notice_type' => $request->notice_type,
            'target_user_type' => $request->target_user_type,
            'target_division_id' => $request->target_division_id,
            'target_user_id' => $request->target_user_id ? json_encode($request->target_user_id) : null,
            'notice_in_software' => $request->has('notice_in_software') ? 1 : 0,
            'send_email' => $request->has('send_email') ? 1 : 0,
            'send_sms' => $request->has('send_sms') ? 1 : 0,
            'send_whatsapp' => $request->has('send_whatsapp') ? 1 : 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id(),
        ]);

        // Mail logic has been removed as per requirement.

        return redirect()->route('admin.notices.index')->with('success', 'Notice published successfully.');
    }

    public function edit(Notice $notice)
    {
        $userTypes = User::select('user_type')->distinct()->whereNotNull('user_type')->pluck('user_type');
        $divisions = Division::where('status', 1)->get();
        $users = User::where('status', 1)->whereNotNull('user_type')->get();
        return view('admin.notices.edit', compact('notice', 'userTypes', 'divisions', 'users'));
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'notice_type' => 'required|in:announcement,warning,new,info',
            'target_user_type' => 'nullable|string',
            'target_division_id' => 'nullable|exists:divisions,id',
            'target_user_id' => 'nullable|array',
            'target_user_id.*' => 'exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['target_user_id'] = $request->has('target_user_id') ? json_encode($request->target_user_id) : null;
        $data['target_user_type'] = $request->target_user_type ?: null;
        $data['target_division_id'] = $request->target_division_id ?: null;
        $data['notice_in_software'] = $request->has('notice_in_software') ? 1 : 0;
        $data['send_email'] = $request->has('send_email') ? 1 : 0;
        $data['send_sms'] = $request->has('send_sms') ? 1 : 0;
        $data['send_whatsapp'] = $request->has('send_whatsapp') ? 1 : 0;

        $notice->update($data);

        return redirect()->route('admin.notices.index')->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('admin.notices.index')->with('success', 'Notice deleted successfully.');
    }
}
