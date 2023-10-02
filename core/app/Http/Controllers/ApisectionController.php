<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Cooperative;
use Illuminate\Http\Request;

class ApisectionController extends Controller
{
    public function getsections()
    {
        dd($manager   = auth()->user()->cooperative_id);
        $sections = Section::where('cooperative_id',3)->with('cooperative')->get();
        return response()->json($sections,201);
    }
}
