<?php

namespace App\Http\Controllers;

use App\Models\Menage;
use App\Models\Localite;
use App\Constants\Status;
use App\Models\Producteur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\VlidateEnfantTotal;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreMenageRequest;

class ApimenageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->id !=null) {
            $menage = Menage::find($request->id);
            $validationRule = [
                'producteur'    => 'required|exists:producteurs,id',
                'quartier' => 'required|max:255',
                'ageEnfant0A5' => ['required','integer', new VlidateEnfantTotal],
                'ageEnfant6A17' => ['required','integer', new VlidateEnfantTotal],
                'enfantscolarises' => ['required','integer', new VlidateEnfantTotal],
                'enfantsPasExtrait' => ['required','integer', new VlidateEnfantTotal],
                'sources_energies'  => 'required|max:255',
                'ordures_menageres'  => 'required|max:255',
                'separationMenage'  => 'required|max:255',
                'eauxToillette'  => 'required|max:255',
                'eauxVaisselle'  => 'required|max:255',
                'wc'  => 'required|max:255',
                'sources_eaux'  => 'required|max:255',
                'equipements'  => 'required|max:255',
                'traitementChamps'  => 'required|max:255',
                'activiteFemme'  => 'required|max:255',
            ];
            $validationMessage = [
                'producteur.required' => 'Le champ producteur est obligatoire',
                'quartier.required' => 'Le champ quartier est obligatoire',
                'ageEnfant0A5.required' => 'Le champ ageEnfant0A5 est obligatoire',
                'ageEnfant6A17.required' => 'Le champ ageEnfant6A17 est obligatoire',
                'enfantscolarises.required' => 'Le champ enfantscolarises est obligatoire',
                'enfantsPasExtrait.required' => 'Le champ enfantsPasExtrait est obligatoire',
                'sources_energies.required' => 'Le champ sources_energies est obligatoire',
                'ordures_menageres.required' => 'Le champ ordures_menageres est obligatoire',
                'separationMenage.required' => 'Le champ separationMenage est obligatoire',
                'eauxToillette.required' => 'Le champ eauxToillette est obligatoire',
                'eauxVaisselle.required' => 'Le champ eauxVaisselle est obligatoire',
                'wc.required' => 'Le champ wc est obligatoire',
                'sources_eaux.required' => 'Le champ sources_eaux est obligatoire',
                'equipements.required' => 'Le champ equipements est obligatoire',
                'traitementChamps.required' => 'Le champ traitementChamps est obligatoire',
                'activiteFemme.required' => 'Le champ activiteFemme est obligatoire',
            ];

        } else {
            $menage = new Menage(); 
            $validationRule = [
                'producteur'    => 'required|exists:producteurs,id',
                'quartier' => 'required|max:255',
                'ageEnfant0A5' => ['required','integer', new VlidateEnfantTotal],
                'ageEnfant6A17' => ['required','integer', new VlidateEnfantTotal],
                'enfantscolarises' => ['required','integer', new VlidateEnfantTotal],
                'enfantsPasExtrait' => ['required','integer', new VlidateEnfantTotal],
                'sources_energies'  => 'required|max:255',
                'ordures_menageres'  => 'required|max:255',
                'separationMenage'  => 'required|max:255',
                'eauxToillette'  => 'required|max:255',
                'eauxVaisselle'  => 'required|max:255',
                'wc'  => 'required|max:255',
                'sources_eaux'  => 'required|max:255',
                'equipements'  => 'required|max:255',
                'traitementChamps'  => 'required|max:255',
                'activiteFemme'  => 'required|max:255',
            ];
            $validationMessage = [
                'producteur.required' => 'Le champ producteur est obligatoire',
                'quartier.required' => 'Le champ quartier est obligatoire',
                'ageEnfant0A5.required' => 'Le champ ageEnfant0A5 est obligatoire',
                'ageEnfant6A17.required' => 'Le champ ageEnfant6A17 est obligatoire',
                'enfantscolarises.required' => 'Le champ enfantscolarises est obligatoire',
                'enfantsPasExtrait.required' => 'Le champ enfantsPasExtrait est obligatoire',
                'sources_energies.required' => 'Le champ sources_energies est obligatoire',
                'ordures_menageres.required' => 'Le champ ordures_menageres est obligatoire',
                'separationMenage.required' => 'Le champ separationMenage est obligatoire',
                'eauxToillette.required' => 'Le champ eauxToillette est obligatoire',
                'eauxVaisselle.required' => 'Le champ eauxVaisselle est obligatoire',
                'wc.required' => 'Le champ wc est obligatoire',
                'sources_eaux.required' => 'Le champ sources_eaux est obligatoire',
                'equipements.required' => 'Le champ equipements est obligatoire',
                'traitementChamps.required' => 'Le champ traitementChamps est obligatoire',
                'activiteFemme.required' => 'Le champ activiteFemme est obligatoire',
            ];
            $request->validate($validationRule, $validationMessage); 
        } 
        if($menage->producteur_id != $request->producteur) {
            $hasMenage = Menage::where('producteur_id', $request->producteur)->exists();
            if ($hasMenage) { 
                return response()->json("Ce producteur a déjà un menage enregistré", 501);
            }
        }
        $menage->producteur_id  = $request->producteur;  
        $menage->quartier  = $request->quartier;
        $menage->ageEnfant0A5  = $request->ageEnfant0A5;
        $menage->ageEnfant6A17  = $request->ageEnfant6A17;
        $menage->enfantscolarises  = $request->enfantscolarises;
        $menage->enfantsPasExtrait = $request->enfantsPasExtrait;
        $menage->sources_energies  = $request->sources_energies;
        $menage->boisChauffe     = $request->boisChauffe;
        $menage->ordures_menageres    = $request->ordures_menageres;
        $menage->separationMenage = $request->separationMenage; 
        $menage->eauxToillette    = $request->eauxToillette;
        $menage->eauxVaisselle    = $request->eauxVaisselle; 
        $menage->wc    = $request->wc; 
        $menage->sources_eaux    = $request->sources_eaux;  
        $menage->type_machines    = $request->type_machines; 
        $menage->garde_machines    = $request->garde_machines; 
        $menage->equipements    = $request->equipements; 
        $menage->traitementChamps    = $request->traitementChamps; 
        $menage->nomApplicateur   = $request->nomApplicateur;
        $menage->numeroApplicateur   = $request->numeroApplicateur;
        $menage->activiteFemme    = $request->activiteFemme; 
        $menage->nomActiviteFemme    = $request->nomActiviteFemme; 
        $menage->champFemme    = $request->champFemme; 
        $menage->nombreHectareFemme    = $request->nombreHectareFemme;
        $menage->autreMachine    = $request->autreMachine;
        $menage->autreEndroit    = $request->autreEndroit;
        $menage->userid = $request->userid;
        $menage->save();  

        if($menage ==null ){
            return response()->json("Le ménage n'a pas été enregistré", 501);
        }
        
        return response()->json($menage, 201);
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
	
        //
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
