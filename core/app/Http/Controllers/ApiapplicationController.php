<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\Localite;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\MatiereActive;
use Illuminate\Support\Str;  
use App\Models\ApplicationMaladie;
use Illuminate\Support\Facades\DB;
use App\Models\ApplicationPesticide;
use Illuminate\Support\Facades\File;
use App\Constants\Status;

class ApiapplicationController extends Controller
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
        $validationRule = [
            'pesticides.*.nom' => 'required|string',
            'pesticides.*.nomCommercial' => 'required|string',
            'pesticides.*.dose' => 'required|integer',
            'pesticides.*.toxicicologie' => 'required|string',
            'pesticides.*.frequence' => 'required|integer',
        ];


        $request->validate($validationRule);
        //dd(response()->json($request));

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivée'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->id) {
            $application = Application::findOrFail($request->id);
            $message = "L'application a été mise à jour avec succès";
        } else {
            $application = new Application();
        }
        $campagne = Campagne::active()->first();
        $application->campagne_id  = $campagne->id;
        $application->applicateur_id  = $request->applicateur;
        $application->parcelle_id  = $request->parcelle_id;
        $application->suiviFormation = $request->suiviFormation;
        $application->attestion = $request->attestion;
        $application->bilanSante = $request->bilanSante;
        $application->independantEpi = $request->independantEpi;
        $application->etatEpi = $request->etatEpi;
        $application->superficiePulverisee = $request->superficiePulverisee;
        $application->delaisReentree = $request->delaisReentree;
        $application->personneApplication = $request->personneApplication;
        $application->date_application = $request->date_application;
        $application->userid = $request->userid;

        $application->save();

        if ($application != null) {
            $id = $application->id;
            if ($request->maladies != null) {
                ApplicationMaladie::where('application_id', $id)->delete();
                $data = [];
                foreach ($request->maladies as $maladie) {
                    $data[] = [
                        'application_id' => $id,
                        'nom' => $maladie,
                    ];
                }
                ApplicationMaladie::insert($data);
            }
            if($request->pesticides[0]['nom'] != null && $request->pesticides[0]['nomCommercial'] != null && $request->pesticides[0]['dose'] != null && $request->pesticides[0]['toxicicologie'] != null && $request->pesticides[0]['frequence'] != null && $request->pesticides[0]['matiereActive'] != null){
                ApplicationPesticide::where('application_id', $id)->delete();
                foreach ($request->pesticides as $pesticide) {
                    $applicationPesticide = new ApplicationPesticide();
                    $applicationPesticide->application_id = $id;
                    $applicationPesticide->nom = $pesticide['nom'];
                    $applicationPesticide->nomCommercial = $pesticide['nomCommercial'];
                    $applicationPesticide->dose = $pesticide['dose'];
                    $applicationPesticide->toxicicologie = $pesticide['toxicicologie'];
                    $applicationPesticide->frequence = $pesticide['frequence'];
                    $applicationPesticide->save();

                    if($applicationPesticide != null){
                        MatiereActive::where('application_pesticide_id', $applicationPesticide->id)->delete();
                        $idApplicationPesticide = $applicationPesticide->id;
                        $matiereActive = explode(',',$pesticide['matiereActive']);
                        foreach ($matiereActive as $matiere) {
                            $applicationMatieresactive = new MatiereActive();
                            $applicationMatieresactive->application_pesticide_id = $idApplicationPesticide;
                            $applicationMatieresactive->nom = $matiere;
                            $applicationMatieresactive->save();
                        }
                    }
                }
            }
           
        }
        return response()->json($application, 201);
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
