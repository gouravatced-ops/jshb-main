<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatchProgram;
use App\Models\DocumentGenerationQueue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BatchProgramController extends Controller
{
    public function emails()
    {
        $batches = BatchProgram::with('details')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.batch_programs.emails', compact('batches'));
    }

    public function documents()
    {
        // Get all queued documents, ordered by newest first.
        $documents = DocumentGenerationQueue::with(['application', 'actionBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by Date (Y-m-d)
        $groupedDocuments = $documents->groupBy(function($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        });

        return view('admin.batch_programs.documents', compact('groupedDocuments'));
    }
}
