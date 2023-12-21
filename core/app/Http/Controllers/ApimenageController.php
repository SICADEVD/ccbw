<?php

namespace App\Http\Controllers;

use App\Models\Menage;
use App\Models\Localite;
use App\Constants\Status;
use App\Models\Producteur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreMenageRequest;


use App\Models\Menage_ordure;
use App\Exports\ExportMenages;
use App\Rules\Enfants0A5PasExtrait;
use App\Http\Controllers\Controller;
use App\Models\Menage_sourceEnergie;
use App\Rules\Enfants6A17PasExtrait;
use App\Rules\NbreEnft6A17Scolarise;
use Illuminate\Support\Facades\Hash;
use App\Rules\Enfants6A17Scolarise;

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

        if ($request->id != null) {
            $menage = Menage::find($request->id);
            $rules = [
                'producteur_id'    => 'required|exists:producteurs,id',
                'quartier' => 'required|max:255',
                'ageEnfant0A5' => ['required', 'integer'],
                'ageEnfant6A17' => ['required', 'integer'],
                'enfantscolarises' => ['required', 'integer',new Enfants6A17Scolarise],
                'enfantsPasExtrait' => ['required', 'integer', new Enfants0A5PasExtrait],
                'enfantsPasExtrait6A17' => ['required', 'integer', new Enfants6A17PasExtrait],
                'separationMenage'  => 'required|max:255',
                'eauxToillette'  => 'required|max:255',
                'eauxVaisselle'  => 'required|max:255',
                'wc'  => 'required|max:255',
                'sources_eaux'  => 'required|max:255',
                'equipements'  => 'required|max:255',
                'traitementChamps'  => 'required|max:255',
                'activiteFemme'  => 'required|max:255',
                'nomApplicateur' => 'required_if:traitementChamps,==,non',
                'numeroApplicateur' => 'required_if:traitementChamps,non|regex:/^\d{10}$/|nullable|unique:menages,numeroApplicateur,'.$request->id,
            ];
            $attributes = [
                'producteur' => 'Producteur',

            ];
            $messages = [
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
                'garde_machines.required' => 'Le champ garde_machines est obligatoire',
                'equipements.required' => 'Le champ equipements est obligatoire',
                'traitementChamps.required' => 'Le champ traitementChamps est obligatoire',
                'activiteFemme.required' => 'Le champ activitFemme est obligatoire',
                'enfantscolarises.max' => 'Le nombre d\'enfants de 6 à 17 ans scolarisés ne peut pas être supérieur au nombre d\'enfants de 6 à 17 ans du ménage',

                'enfantsPasExtrait6A17.max' => 'Le nombre d\'enfants de 6 à 17 n\'ayant pas d\'extrait ne peut pas être supérieur au nombre d\'enfants de 6 à 17 ans du ménage',

                'enfantsPasExtrait.max'=>'Le nombre d\'enfants de 0 à 5 n\'ayant pas d\'extrait ne peut pas être supérieur au nombre d\'enfants de 0 à 5 ans du ménage',
            ];
            $this->validate($request, $rules, $messages, $attributes);
        } else {
            $menage = new Menage();
            $rules = [
                'producteur_id'    => 'required|exists:producteurs,id',
                'quartier' => 'required|max:255',
                'ageEnfant0A5' => ['required', 'integer'],
                'ageEnfant6A17' => ['required', 'integer'],
                'enfantscolarises' => ['required', 'integer',new Enfants6A17Scolarise],
                'enfantsPasExtrait' => ['required', 'integer', new Enfants0A5PasExtrait],
                'enfantsPasExtrait6A17' => ['required', 'integer', new Enfants6A17PasExtrait],
                'separationMenage'  => 'required|max:255',
                'eauxToillette'  => 'required|max:255',
                'eauxVaisselle'  => 'required|max:255',
                'wc'  => 'required|max:255',
                'sources_eaux'  => 'required|max:255',
                'equipements'  => 'required|max:255',
                'traitementChamps'  => 'required|max:255',
                'activiteFemme'  => 'required|max:255',
                'nomApplicateur' => 'required_if:traitementChamps,==,non',
                'numeroApplicateur' => 'required_if:traitementChamps,==,non|regex:/^\d{10}$/|nullable|unique:menages,numeroApplicateur',
            ];
            $attributes = [
                'producteur' => 'Producteur',

            ];
            $messages = [
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
                'garde_machines.required' => 'Le champ garde_machines est obligatoire',
                'equipements.required' => 'Le champ equipements est obligatoire',
                'traitementChamps.required' => 'Le champ traitementChamps est obligatoire',
                'activiteFemme.required' => 'Le champ activitFemme est obligatoire',
            ];
            $this->validate($request, $rules, $messages, $attributes);
        }
        if ($menage->producteur_id != $request->producteur_id) {
            $hasMenage = Menage::where('producteur_id', $request->producteur_id)->exists();
            if ($hasMenage) {
                return response()->json("Ce producteur a déjà un menage enregistré", 501);
            }
        }
        $menage->producteur_id  = $request->producteur_id;
        $menage->quartier  = $request->quartier;
        $menage->ageEnfant0A5  = $request->ageEnfant0A5;
        $menage->ageEnfant6A17  = $request->ageEnfant6A17;
        $menage->enfantscolarises  = $request->enfantscolarises;
        $menage->enfantsPasExtrait = $request->enfantsPasExtrait;
        $menage->enfantsPasExtrait6A17 = $request->enfantsPasExtrait6A17;
        $menage->boisChauffe     = $request->boisChauffe;
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
        $menage->userid =  $request->userid;
        $menage->autreSourceEau   = $request->autreSourceEau;
        $menage->etatAutreMachine   = $request->etatAutreMachine;
        $menage->etatatomiseur   = $request->etatatomiseur;
        $menage->etatEpi  = $request->etatEpi;
        $menage->typeActivite = $request->typeActivite;
        $menage->nomActiviteAgricole = $request->nomActiviteAgricole;
        $menage->autreActiviteAgricole = $request->autreActiviteAgricole;
        $menage->nomActiviteNonAgricole = $request->nomActiviteNonAgricole;
        $menage->autreActiviteNonAgricole = $request->autreActiviteNonAgricole;
        $menage->capitalDemarrage = $request->capitalDemarrage;
        $menage->formation = $request->formation;
        $menage->dureeActivite = $request->dureeActivite;
        $menage->autreCapital = $request->autreCapital;
        $menage->nombreHectareConjoint = $request->nombreHectareConjoint;
        $menage->entite = $request->entite;
        //dd(json_encode($request->all()));

        $menage->save();
        if ($menage != null) {
            $id = $menage->id;
            $datas  = $data2 = [];
            if (($request->sourcesEnergie != null)) {
                Menage_sourceEnergie::where('menage_id', $id)->delete();
                $i = 0;
                foreach ($request->sourcesEnergie as $sourceEnergie) {
                    if (!empty($sourceEnergie)) {
                        $datas[] = [
                            'menage_id' => $id,
                            'source_energie' => $sourceEnergie,
                        ];
                    }

                    $i++;
                }
            }
            if (($request->ordureMenagere != null)) {
                Menage_ordure::where('menage_id', $id)->delete();
                foreach ($request->ordureMenagere as $data) {
                    //dd($ordureMenagere);

                    $data2[] = [
                        'menage_id' => $id,
                        'ordure_menagere' => $data,
                    ];
                }
            }

            Menage_sourceEnergie::insert($datas);
            Menage_ordure::insert($data2);
        }

        if ($menage == null) {
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
