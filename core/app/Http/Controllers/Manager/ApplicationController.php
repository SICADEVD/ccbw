<?php

namespace App\Http\Controllers\Manager;

use Excel;
use App\Models\User;
use App\Models\Campagne;
use App\Constants\Status;
use App\Models\Localite;
use App\Models\Parcelle;
use App\Models\Application;
use App\Models\Cooperative;
use App\Models\Producteur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ApplicationInsecte;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportApplications;
use App\Http\Controllers\Controller;
use App\Models\ApplicationMaladie;
use Illuminate\Support\Facades\Hash;
use App\Models\ApplicationMatieresactive;
use App\Models\ApplicationPesticide;
use App\Models\MatiereActive;

class ApplicationController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des applications";
        $manager   = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->get();
        $applications = Application::dateFilter()->searchable(["superficiePulverisee"])->latest('id')->joinRelationship('parcelle.producteur.localite.section')->where('cooperative_id', $manager->cooperative_id)->where(function ($q) {
            if (request()->localite != null) {
                $q->where('localite_id', request()->localite);
            }
        })->with('parcelle')->paginate(getPaginate());

        return view('manager.application.index', compact('pageTitle', 'applications', 'localites'));
    }

    public function create()
    {

        $pageTitle = "Ajouter une application";
        $manager = auth()->user();

        $producteurs = Producteur::with('localite')->get();

        $cooperative = Cooperative::with('sections.localites.section')->find($manager->cooperative_id);

        $sections = $cooperative->sections;

        $localites = $cooperative->sections->flatMap(function ($section) {
            return $section->localites->filter(function ($localite) {
                return $localite->active();
            });
        });

        $campagnes = Campagne::active()->pluck('nom', 'id');
        $parcelles = Parcelle::with('producteur')->get();

        $staffs = User::whereHas('roles', function($q){ $q->whereIn('name', ['Applicateur']); })
        ->where('cooperative_id', $manager->cooperative_id)
        ->select('users.*')
        ->get(); 
       
        return view('manager.application.create', compact('pageTitle', 'producteurs', 'localites', 'parcelles', 'staffs', 'sections', 'campagnes'));
    }

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
        $application->userid = auth()->user()->id;

        
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
                            $applicationMatieresactive->application_id = $id;
                            $applicationMatieresactive->application_pesticide_id = $idApplicationPesticide;
                            $applicationMatieresactive->nom = $matiere;
                            $applicationMatieresactive->save();
                        }
                    }
                }
            }
           
        }
        $notify[] = ['success', isset($message) ? $message : "L'application a été crée avec succès."];
        return back()->withNotify($notify);
    }



    public function edit($id)
    {
        $pageTitle = "Mise à jour de la application";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $parcelles = Parcelle::with('producteur')->get();
        $staffs = User::whereHas('roles', function($q){ $q->whereIn('name', ['Applicateur']); });
        $campagnes = Campagne::active()->pluck('nom', 'id');
        $cooperative = Cooperative::with('sections.localites', 'sections.localites.section')->find($manager->cooperative_id);
        $sections = $cooperative->sections;
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $application   = Application::findOrFail($id);

        $applicationPesticides = $application->applicationPesticides;
        $matieresActives = MatiereActive::where('application_id', $id)->get();

        $applicationPesticides->map(function ($applicationPesticide) use ($matieresActives) {
            return $applicationPesticide->matieresActives = $matieresActives->where('application_pesticide_id', $applicationPesticide->id)->pluck('nom');
        })->all();
        $applicationMaladies = $application->applicationMaladies->pluck('nom')->all();
        
        

       
        return view('manager.application.edit', compact('pageTitle', 'application', 'producteurs', 'localites', 'parcelles', 'staffs', 'sections', 'campagnes', 'applicationPesticides', 'applicationMaladies'));
    }

    public function status($id)
    {
        return Application::changeStatus($id);
    }

    public function exportExcel()
    {
        $filename = 'applications-' . gmdate('dmYhms') . '.xlsx';
        return Excel::download(new ExportApplications, $filename);
    }
}
