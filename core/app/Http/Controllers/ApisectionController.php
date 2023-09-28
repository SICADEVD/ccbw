<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class ApisectionController extends Controller
{
    public function getsections()
    {
        $sections = Section::orderBy('created_at','desc')->with('cooperative')->get();
        return response()->json($sections,201);
    }
}
