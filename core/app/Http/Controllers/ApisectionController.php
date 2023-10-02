<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Cooperative;
use Illuminate\Http\Request;

class ApisectionController extends Controller
{
    public function getsections()
    {
        dd('hello');
        $manager   = auth()->user();
        $sections = Section::where('cooperative_id',$manager->cooperative_id)->with('cooperative')->get();
        return response()->json($sections,201);
    }
}
