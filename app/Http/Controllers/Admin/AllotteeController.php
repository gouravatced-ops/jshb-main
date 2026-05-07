<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allottee;
use App\Models\Division;
use App\Models\AllotteesContactDetail;
use App\Models\RegistrationFile;
use App\Models\RegisterAllottee;
use App\Models\AllotteeMasterDocument;
use App\Models\SubDivision;
use App\Models\StepSkip;
use App\Models\AllotteePropertyFinDetail;
use App\Models\AllotteeNomineeBankDetail;
use App\Models\AllotteeEmiLedger;
use App\Models\AllotteeDocument;
use App\Models\AllotteeStepDuration;
use App\Models\DocumentMaster;
use App\Models\QuarterType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AllotteeController extends Controller
{
    public function index()
    {
        $allottees = Allottee::with('division')->latest()->paginate(10);
        return view('admin.allottee.index', compact('allottees'));
    }

    public function indexStart()
    {
        return view('admin.allottee.index');
    }

    // In your controller
    public function getStep($step, $applicantId = null)
    {
        $allottee = $applicantId ? Allottee::find($applicantId) : null;
        $view = "admin.allottee.step{$step}";
        switch ($step) {
            case 1:
                return view($view, compact('allottee'));
            case 2:
                return view($view, compact('allottee'));
            case 3:
                return view($view, compact('allottee'));
        }
    }

    public function create()
    {
        $divisions = Division::where('status', 1)->get();
        $allottee = Allottee::where('id', 1)->first();
        return view('admin.allottee.add', compact('allottee', 'divisions'));
    }

    public function saveStep1(Request $request)
    {
        // return $request;
        return response()->json([
            'success' => true,
            'message' => 'Allottee Details updated successfully',
            'applicant_id' => 2,
            'next_step' => 2
        ]);
    }

    public function saveStep2(Request $request)
    {
        // return $request;
        return response()->json([
            'success' => true,
            'message' => 'Allottee Details updated successfully',
            'applicant_id' => 2,
            'next_step' => 3
        ]);
    }

    public function saveStep3(Request $request)
    {
        // return $request;
        return response()->json([
            'success' => true,
            'message' => 'Application Submit Successfully',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_no' => 'nullable|string|max:255',
            'application_date' => 'nullable|date',
            'allotment_no' => 'nullable|string|max:255',
            'allotment_date' => 'nullable|date',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'first_name_hi' => 'nullable|string|max:255',
            'middle_name_hi' => 'nullable|string|max:255',
            'relation_name' => 'nullable|string|max:255',
            'relation_type' => 'nullable|string|max:100',
            'marital_status' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'current_age' => 'nullable|integer',
            'primary_mobile' => 'nullable|string|max:10|min:10',
            'whatsapp_no' => 'nullable|string|max:10|min:10',
            'alternate_mobile' => 'nullable|string|max:10|min:10',
            'email_id' => 'nullable|email|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Allottee::create($request->all());

        return redirect()->route('admin.allottees.index')
            ->with('success', 'Allottee created successfully.');
    }

    public function edit(Allottee $allottee)
    {
        $divisions = Division::where('status', 1)->get();
        return view('admin.allottee.edit', compact('allottee', 'divisions'));
    }

    public function update(Request $request, Allottee $allottee)
    {
        $validator = Validator::make($request->all(), [
            'application_no' => 'nullable|string|max:255',
            'application_date' => 'nullable|date',
            'allotment_no' => 'nullable|string|max:255',
            'allotment_date' => 'nullable|date',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'first_name_hi' => 'nullable|string|max:255',
            'middle_name_hi' => 'nullable|string|max:255',
            'relation_name' => 'nullable|string|max:255',
            'relation_type' => 'nullable|string|max:100',
            'marital_status' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'current_age' => 'nullable|integer',
            'primary_mobile' => 'nullable|string|max:10|min:10',
            'whatsapp_no' => 'nullable|string|max:10|min:10',
            'alternate_mobile' => 'nullable|string|max:10|min:10',
            'email_id' => 'nullable|email|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $allottee->update($request->all());

        return redirect()->route('admin.allottees.index')
            ->with('success', 'Allottee updated successfully.');
    }

    public function destroy(Allottee $allottee)
    {
        $allottee->delete();
        return redirect()->route('admin.allottees.index')
            ->with('success', 'Allottee deleted successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $allottees = Allottee::with('division')
            ->where('application_no', 'like', "%{$search}%")
            ->orWhere('allotment_no', 'like', "%{$search}%")
            ->orWhere('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('primary_mobile', 'like', "%{$search}%")
            ->paginate(10);

        return view('admin.allottee.index', compact('allottees'));
    }
}
