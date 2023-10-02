<?php

namespace App\Http\Controllers\Manager;

use App\Models\Section;
use App\Models\Localite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Cooperative;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SectionController extends Controller
{
    public function index()
    {
        $pageTitle = "Gestion des sections"; 
        $manager   = auth()->user();
        $cooperatives = Cooperative::active()->where('id',$manager->cooperative_id)->get();
        $sections = Section::orderBy('created_at','desc')->with('cooperative')->paginate(getPaginate());
        
        return view('manager.section.index',compact('pageTitle','cooperatives','sections'));
    }

    public function create()
    {
        $pageTitle = "Ajouter une section";
        $manager   = auth()->user();
        $cooperatives  = Cooperative::active()->where('id',$manager->cooperative_id)->orderBy('name')->get();
        return view('manager.section.create', compact('pageTitle','cooperatives'));
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
            $manager   = auth()->user();
            $cooperatives  = Cooperative::active()->where('id',$manager->cooperative_id)->orderBy('name')->get();
            return view('manager.section.edit', compact('pageTitle','section','cooperatives'));
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
    //lister les localités d'une section
    public function localiteSection($id)
    {
        $pageTitle = "Gestion des localités de la section ". Section::find($id)->libelle;
        $cooperativeLocalites = Localite::active()->where('section_id',$id)->with('section.cooperative')->paginate(getPaginate());
        return view('manager.localite.index',compact('cooperativeLocalites','pageTitle'));
    }
    //traitement pour enregistrer une localité d'une section
    public function storelocalitesection(){

    }

    //traitement pour modifier une localité d'une section
    public function updatelocalitesection($id){

    }
    //affichant le formulaire de modification d'une localité d'une section
    public function localitesectionedit($id){

    }
}
