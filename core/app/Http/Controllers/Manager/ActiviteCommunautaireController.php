<?php

namespace App\Http\Controllers\Manager;

use App\Models\Localite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActiviteCommunautaire;

class ActiviteCommunautaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle      = "Gestion des Activités Communautaires";
        $manager   = auth()->user(); 
        $activites = ActiviteCommunautaire::dateFilter()->searchable([])->latest('id')->where('cooperative_id', $manager->cooperative_id)->where(function ($q) {
            if (request()->localite != null) {
                $q->where('localite_id', request()->localite);
            }
        })->with('cooperative')->paginate(getPaginate());

        return view('manager.activite-communautaire.index', compact('pageTitle', 'activites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Ajouter une Activité Communautaire";
        $manager = auth()->user(); 
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->orderBy('nom')->get();
       
        return view('manager.activite-communautaire.create', compact('pageTitle','localites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = "Modifier une Activité Communautaire";
        $manager = auth()->user(); 
        $actionSociale = ActiviteCommunautaire::find($id); // Remplacez ActionSociale par le nom de votre modèle
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->orderBy('nom')->get();
        return view('manager.activite-communautaire.edit', compact('actionSociale','localites'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
