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
use Illuminate\Support\Facades\Hash;
use App\Models\ApplicationMatieresactive;

class ApplicationController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des applications";
        $manager   = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->get();
        $applications = Application::dateFilter()->searchable(["superficiePulverisee", "marqueProduitPulverise", "matieresActives", "degreDangerosite", "raisonApplication", "nomInsectesCibles", "delaisReentree", "zoneTampons", "presenceDouche"])->latest('id')->joinRelationship('parcelle.producteur.localite.section')->where('cooperative_id', $manager->cooperative_id)->where(function ($q) {
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
            'pesticides.*.dosage' => 'required|integer',
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
        $application->suiviFormation = $request->suiviFormation;
        $application->attestion = $request->attestion;
        $application->bilanSante = $request->bilanSante;
        $application->independantEpi = $request->independantEpi;
        $application->etatEpi = $request->etatEpi;
        $application->superficiePulverisee = $request->superficiePulverisee;
        $application->delaisReentree = $request->delaisReentree;
        $application->date_application = $request->date_application;

        dd($request->all());

        $application->save();

        if ($application != null) {
            $id = $application->id;
        }
        $notify[] = ['success', isset($message) ? $message : "L'application a été crée avec succès."];
        return back()->withNotify($notify);
    }



    public function edit($id)
    {
        $pageTitle = "Mise à jour de la application";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites  = Localite::active()->where('cooperative_id', auth()->user()->cooperative_id)->orderBy('nom')->get();
        $parcelles  = Parcelle::with('producteur')->get();
        $staffs  = User::staff()->get();
        $application   = Application::findOrFail($id);
        return view('manager.application.edit', compact('pageTitle', 'application', 'producteurs', 'localites', 'parcelles', 'staffs'));
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
