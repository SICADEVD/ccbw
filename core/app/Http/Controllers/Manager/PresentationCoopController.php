<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PresentationCoopController extends Controller
{
    public function index()
    {
        return view('manager.presentation-coop.index');
    }

    public function create()
    {
        return view('manager.presentation-coop.create');
    }

    public function edit()
    {
        return view('manager.presentation-coop.edit');
    }
    public function store(Request $request)
    {
        return redirect()->route('manager.presentation-coop.index');
    }
}
