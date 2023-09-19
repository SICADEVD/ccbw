<?php

namespace App\Http\Controllers\Manager;

use App\Models\Programme;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgrammeDurabilite;
use App\Http\Requests\UpdateProgrammeDurabilite;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProgrammeController extends Controller
{
    public function index()
    {
        $pageTitle = "Gestion des programmes de durabilité";
        $programmeDurabilites = Programme::orderBy('created_at','desc')->paginate(getPaginate());
        return view('manager.programmeDurabilite.index',compact('pageTitle','programmeDurabilites'));
    }

    public function create()
    {
        $pageTitle = "Ajouter un programme de durabilité";
        return view('manager.programmeDurabilite.create',compact('pageTitle'));
    }

    public function store(StoreProgrammeDurabilite $request)
    {
        $valitedData = $request->validated();
            
        Programme::create($valitedData);
    
        $notify[] = ['success', isset($message) ? $message : 'Le programme de durabilité a été crée avec succès.'];
        return back()->withNotify($notify);
    
    }

    public function edit($id)
    {
        $pageTitle = "Modifier un programme de durabilité";

        try {
            $programme = Programme::findOrFail($id);
            return view('manager.programmeDurabilite.edit', compact('pageTitle','programme'));
        } catch (ModelNotFoundException $e) {
            // L'enregistrement n'a pas été trouvé, vous pouvez rediriger ou afficher un message d'erreur
            return redirect()->route('manager.durabilite.index')->with('error', 'La section demandée n\'existe pas.');
        }
    }
    public function update(UpdateProgrammeDurabilite $request, $id)
    {
        $valitedData = $request->validated();
        try {
            $programme = Programme::findOrFail($id);
            $programme->update($valitedData);
            $notify[] = ['success', isset($message) ? $message : 'Le programme de durabilité a été modifié avec succès.'];
            return redirect()->route('manager.durabilite.index')->withNotify($notify);
        } catch (ModelNotFoundException $e) {
            // L'enregistrement n'a pas été trouvé, vous pouvez rediriger ou afficher un message d'erreur
            return redirect()->route('manager.durabilite.index')->with('error', 'La section demandée n\'existe pas.');
        }
    }
    

}
