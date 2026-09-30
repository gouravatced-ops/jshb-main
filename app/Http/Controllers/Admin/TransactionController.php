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

        // Simple filtering
        if ($request->filled('transaction_no')) {
            $query->where('transaction_no', 'like', '%' . $request->transaction_no . '%');
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $transactions = $query->paginate(15);

        return view('admin.transactions.index', compact('transactions'));
    }

}
