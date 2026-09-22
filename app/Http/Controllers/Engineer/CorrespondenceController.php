<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationCorrespondence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericNotificationMail;
use Illuminate\Support\Facades\Log;

class CorrespondenceController extends Controller
{
    public function create(Application $application)
    {
        return view('engineer.applications.correspondence.create', compact('application'));
    }

    public function store(Request $request, Application $application)
    {
        $request->validate([
            'type' => 'required|in:LT,OO,OD',
            'subject' => 'required|string|max:255',
            'font_family' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,published'
        ]);

        if ($request->status === 'published' && $request->input('otp_verified') != '1') {
            return redirect()->back()->with('error', 'Please verify OTP first before publishing.');
        }

        $divisionCode = $application->allottee->division->division_code ?? 'HQ';

        $referenceNumber = ApplicationCorrespondence::generateReferenceNumber($request->type, $divisionCode);

        $correspondence = ApplicationCorrespondence::create([
            'application_id' => $application->id,
            'generated_by_user_id' => Auth::id(),
            'type' => $request->type,
            'reference_number' => $referenceNumber,
            'subject' => $request->subject,
            'font_family' => $request->font_family ?? 'english',
            'content' => $request->content,
            'status' => $request->status,
            'otp_verified' => $request->input('otp_verified', 0),
        ]);

        if ($correspondence->status === 'published') {
            $this->sendPublishedEmail($correspondence, $application);
        }

        return redirect()->route('engineer.applications.show', $application)
            ->with('success', 'Correspondence generated successfully. Reference No: ' . $referenceNumber);
    }

    public function edit(Application $application, ApplicationCorrespondence $correspondence)
    {
        if ($correspondence->application_id !== $application->id) {
            abort(404);
        }

        if ($correspondence->generated_by_user_id !== Auth::id()) {
            abort(403, 'Unauthorized. Only the creator can edit this correspondence.');
        }

        if ($correspondence->status === 'published') {
            return redirect()->route('engineer.applications.show', $application)
                ->with('error', 'Published correspondence cannot be edited.');
        }

        return view('engineer.applications.correspondence.edit', compact('application', 'correspondence'));
    }

    public function update(Request $request, Application $application, ApplicationCorrespondence $correspondence)
    {
        if ($correspondence->application_id !== $application->id) {
            abort(404);
        }

        if ($correspondence->generated_by_user_id !== Auth::id()) {
            abort(403, 'Unauthorized. Only the creator can edit this correspondence.');
        }

        if ($correspondence->status === 'published') {
            return redirect()->route('engineer.applications.show', $application)
                ->with('error', 'Published correspondence cannot be edited.');
        }

        $request->validate([
            'type' => 'required|in:LT,OO,OD',
            'subject' => 'required|string|max:255',
            'font_family' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,published'
        ]);

        if ($request->status === 'published' && $request->input('otp_verified') != '1') {
            return redirect()->back()->with('error', 'Please verify OTP first before publishing.');
        }

        $correspondence->update([
            'type' => $request->type,
            'subject' => $request->subject,
            'font_family' => $request->font_family ?? 'english',
            'content' => $request->content,
            'status' => $request->status,
            'otp_verified' => $request->input('otp_verified', 0),
        ]);

        if ($request->status === 'published') {
            $this->sendPublishedEmail($correspondence, $application);
        }

        $message = $request->status === 'published' ? 'Correspondence published successfully.' : 'Draft updated successfully.';

        return redirect()->route('engineer.applications.show', $application)
            ->with('success', $message);
    }

    public function show(Application $application, ApplicationCorrespondence $correspondence)
    {
        if ($correspondence->application_id !== $application->id) {
            abort(404);
        }

        return view('engineer.applications.correspondence.show', compact('application', 'correspondence'));
    }

    private function sendPublishedEmail($correspondence, $application)
    {
        $user = Auth::user();
        if (!$user || !$user->email) return;

        $systemEmail = 'system@adms.jshb.computered.co.in';
        $subject = "Correspondence Published - Reference # {$correspondence->reference_number}";

        $mailBody = "Dear {$user->name},\n\n";
        $mailBody .= "A new correspondence letter has been successfully generated and published.\n\n";
        $mailBody .= "Application No: {$application->application_no}\n";
        $mailBody .= "Reference Number: {$correspondence->reference_number}\n";
        $mailBody .= "Letter Type: {$correspondence->type}\n\n";
        $mailBody .= "This order letter is now officially attached to the application.";

        try {
            Mail::to($user->email)->cc($systemEmail)->send(new GenericNotificationMail($subject, $mailBody, null));
        } catch (\Exception $e) {
            Log::error("Failed to send correspondence email: " . $e->getMessage());
        }
    }
}
