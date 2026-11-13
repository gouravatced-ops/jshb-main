<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AllotteeTransaction;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = AllotteeTransaction::with('allottee')->orderBy('created_at', 'desc');

        // Search by transaction_no, utr_no, or allottee info
        if ($request->filled('transaction_no')) {
            $search = trim($request->transaction_no);
            $query->where(function ($q) use ($search) {
                $q->where('transaction_no', 'like', '%' . $search . '%')
                  ->orWhere('utr_no', 'like', '%' . $search . '%')
                  ->orWhereHas('allottee', function ($aq) use ($search) {
                      $aq->where('property_number', 'like', '%' . $search . '%')
                         ->orWhere('allottee_name', 'like', '%' . $search . '%')
                         ->orWhere('username', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'success') {
                $query->whereIn('payment_status', ['success', 'paid', 'completed']);
            } else {
                $query->where('payment_status', $status);
            }
        }

        if ($request->filled('payment_mode')) {
            $query->where('payment_mode', $request->payment_mode);
        }

        // Summary metrics
        $totalCount = AllotteeTransaction::count();
        $successCount = AllotteeTransaction::whereIn('payment_status', ['success', 'paid', 'completed'])->count();
        $pendingCount = AllotteeTransaction::where('payment_status', 'pending')->count();
        $failedCount = AllotteeTransaction::where('payment_status', 'failed')->count();
        $totalSuccessAmount = AllotteeTransaction::whereIn('payment_status', ['success', 'paid', 'completed'])->sum('total_amount');

        $transactions = $query->paginate(15)->withQueryString();

        return view('admin.transactions.index', compact(
            'transactions',
            'totalCount',
            'successCount',
            'pendingCount',
            'failedCount',
            'totalSuccessAmount'
        ));
    }

}
