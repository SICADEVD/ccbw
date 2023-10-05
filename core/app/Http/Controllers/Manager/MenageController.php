<?php

namespace App\Http\Controllers\Manager;

use App\Models\Menage; 
use App\Models\Section;
use App\Constants\Status;
use App\Models\Localite; 
use App\Models\Cooperative;
use App\Models\Producteur; 
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ExportMenages;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreMenageRequest;

class MenageController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des menages";
        $manager   = auth()->user();
        $cooperative = Cooperative::with('sections.localites')->find($manager->cooperative_id);
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $menages = Menage::dateFilter()->searchable(["quartier","sources_energies","boisChauffe","ordures_menageres","separationMenage","eauxToillette","eauxVaisselle","wc","menages.sources_eaux","type_machines","garde_machines","equipements","traitementChamps","activiteFemme","nomActiviteFemme","champFemme","nombreHectareFemme"])->latest('id')->joinRelationship('producteur.localite.section')->where('cooperative_id',$manager->cooperative_id)->where(function ($q) {
            if(request()->localite != null){
                $q->where('localite_id',request()->localite);
            }
        })->with('producteur')->paginate(getPaginate());
         
        return view('manager.menage.index', compact('pageTitle', 'menages','localites'));
    }
 
    public function create()
    {
        $pageTitle = "Ajouter un menage";
        $manager   = auth()->user();
        $cooperative = Cooperative::with('sections.localites')->find($manager->cooperative_id);
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $producteurs  = Producteur::with('localite')->get();
        
        return view('manager.menage.create', compact('pageTitle', 'producteurs','localites'));
    }

    public function store(StoreMenageRequest $request)
    {
        $request->validated();
        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivé'];
            return back()->withNotify($notify)->withInput();
        }
        
        if($request->id) {
            $menage = Menage::findOrFail($request->id); 
            $message = "Le menage a été mise à jour avec succès";

        } else {
            $menage = new Menage();  
        } 
        if($menage->producteur_id != $request->producteur) {
            $hasMenage = Menage::where('producteur_id', $request->producteur)->exists();
            if ($hasMenage) {
                $notify[] = ['error', 'Ce producteur a déjà un menage enregistré'];
                return back()->withNotify($notify)->withInput();
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
        // dd($menage);
        $menage->save(); 

        $notify[] = ['success', isset($message) ? $message : 'Le menage a été crée avec succès.'];
        return back()->withNotify($notify);
    }
 

    public function edit($id)
    {
        $pageTitle = "Mise à jour de le menage";
        $manager   = auth()->user();
        // $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        $producteurs  = Producteur::with('localite')->get();
        $cooperative = Cooperative::with('sections.localites')->find($manager->cooperative_id);
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $menage   = Menage::findOrFail($id);
        return view('manager.menage.edit', compact('pageTitle', 'localites', 'menage','producteurs'));
    } 

    public function status($id)
    {
        return Menage::changeStatus($id);
    }

    public function exportExcel()
    {
        return (new ExportMenages())->download('menages.xlsx');
    }

}
