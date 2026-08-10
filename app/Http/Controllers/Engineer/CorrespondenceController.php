<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationCorrespondence;
use Illuminate\Support\Facades\Auth;

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
        ]);

        return redirect()->route('engineer.applications.show', $application)
            ->with('success', 'Correspondence generated successfully. Reference No: ' . $referenceNumber);
    }

    public function edit(Application $application, ApplicationCorrespondence $correspondence)
    {
        if ($correspondence->application_id !== $application->id) {
            abort(404);
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

        $correspondence->update([
            'type' => $request->type,
            'subject' => $request->subject,
            'font_family' => $request->font_family ?? 'english',
            'content' => $request->content,
            'status' => $request->status,
        ]);

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
}
