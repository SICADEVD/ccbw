<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Cooperative;
use Illuminate\Http\Request;

class ApisectionController extends Controller
{
    public function getsections()
    {
        //$manager   = auth()->user();
        $sections = Section::where('cooperative_id',3)->with('cooperative')->get();
        return response()->json($sections,201);
    }
}
