<?php

namespace App\Http\Controllers\Manager;

use App\Models\Section;
use App\Models\Localite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SectionController extends Controller
{
    public function index()
    {
        $pageTitle = "Gestion des sections"; 
        $sections = Section::orderBy('created_at','desc')->with('localite')->paginate(getPaginate());
        $localites = Localite::active()->orderBy('nom')->get();
        
        return view('manager.section.index',compact('pageTitle','sections','localites'));
    }

    public function create()
    {
        $pageTitle = "Ajouter une section";
        $localites = Localite::active()->orderBy('nom')->get();
        return view('manager.section.create', compact('pageTitle','localites'));
    }

    public function store(StoreSectionRequest $request){
        $valitedData = $request->validated();

        Section::create($valitedData);

        $notify[] = ['success', isset($message) ? $message : 'La section a été crée avec succès.'];
        return back()->withNotify($notify);

    }
    public function edit($id)
    {
        $pageTitle = "Modifier une section";

        try {
            $section = Section::findOrFail($id);
            $localites = Localite::active()->orderBy('nom')->get();
            return view('manager.section.edit', compact('pageTitle','section','localites'));
        } catch (ModelNotFoundException $e) {
            // L'enregistrement n'a pas été trouvé, vous pouvez rediriger ou afficher un message d'erreur
            return redirect()->route('manager.section.index')->with('error', 'La section demandée n\'existe pas.');
        }
      
    }

    public function update(UpdateSectionRequest $request, $id)
    {
        $valitedData = $request->validated();
        $section = Section::findOrFail($id);
        $section->update($valitedData);
        $notify[] = ['success', isset($message) ? $message : 'La section a été mise à jour avec succès.'];
        return back()->withNotify($notify);
    }
}
