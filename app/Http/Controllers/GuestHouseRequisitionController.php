<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestHouseRequisitionController extends Controller
{
    public function userIndex()
    {
        return view('user.requisitions.index');
    }

    public function create()
    {
        return view('user.requisitions.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('requisitions.index');
    }
}
