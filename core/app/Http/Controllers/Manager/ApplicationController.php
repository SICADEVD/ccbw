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

        $staffs  = User::staff()->get();
        return view('manager.application.create', compact('pageTitle', 'producteurs', 'localites', 'parcelles', 'staffs', 'sections', 'campagnes'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'parcelle_id'    => 'required|exists:parcelles,id',
            'applicateur'    => 'required|exists:users,id',
            'superficiePulverisee'  => 'required|max:255',
            'marqueProduitPulverise'  => 'required|max:255',
            'raisonApplication'  => 'required|max:255',
            'delaisReentree'  => 'required|max:255',
            'date_application'  => 'required|max:255',
            'heure_application'  => 'required|max:255',
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
        $application->parcelle_id  = $request->parcelle_id;
        $application->campagne_id  = $campagne->id;
        $application->applicateur_id  = $request->applicateur;
        $application->superficiePulverisee  = $request->superficiePulverisee;
        $application->marqueProduitPulverise  = $request->marqueProduitPulverise;
        $application->degreDangerosite  = $request->degreDangerosite;
        $application->raisonApplication  = $request->raisonApplication;
        $application->delaisReentree  = $request->delaisReentree;
        $application->zoneTampons  = $request->zoneTampons;
        $application->presenceDouche  = $request->presenceDouche;
        $application->date_application  = $request->date_application;
        $application->heure_application = $request->heure_application;
        if ($request->hasFile('photoZoneTampons')) {
            try {
                $application->photoZoneTampons = $request->file('photoZoneTampons')->store('public/applications');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('photoDouche')) {
            try {
                $application->photoDouche = $request->file('photoDouche')->store('public/applications');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        }

        $application->save();


        if ($application != null) {
            $id = $application->id;
            $datas = [];
            $datas2 = [];

            if (count($request->matieresActives)) {
                ApplicationMatieresactive::where('application_id', $id)->delete();
                $i = 0;
                foreach ($request->matieresActives as $data) {

                    $datas[] = [
                        'application_id' => $id,
                        'matiereactive' => $data,
                    ];
                }
            }
            if (count($request->nomInsectesCibles)) {
                ApplicationInsecte::where('application_id', $id)->delete();
                $i = 0;
                foreach ($request->nomInsectesCibles as $data) {

                    $datas2[] = [
                        'application_id' => $id,
                        'insecte' => $data,
                    ];
                }
            }
            ApplicationMatieresactive::insert($datas);
            ApplicationInsecte::insert($datas2);
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
