<?php

namespace App\Http\Controllers\Staff;

use App\Constants\Status;
use App\Exports\ExportMenages;
use App\Http\Controllers\Controller;
use App\Models\Localite; 
use App\Models\Producteur; 
use App\Models\Menage; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MenageController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des menages";
        $manager   = auth()->user();
        $localites = Localite::active()->where('cooperative_id',$manager->cooperative_id)->get();
        $menages = Menage::dateFilter()->searchable(["quartier","sources_energies","boisChauffe","ordures_menageres","separationMenage","eauxToillette","eauxVaisselle","wc","menages.sources_eaux","machine","type_machines","garde_machines","equipements","traitementChamps","nomPersonneTraitant","numeroPersonneTraitant","empruntMachine","gardeEmpruntMachine","activiteFemme","nomActiviteFemme","superficieCacaoFemme","champFemme","nombreHectareFemme"])->latest('id')->joinRelationship('producteur.localite')->where('cooperative_id',$manager->cooperative_id)->where(function ($q) {
            if(request()->localite != null){
                $q->where('localite_id',request()->localite);
            }
        })->with('producteur')->paginate(getPaginate());
         
        return view('staff.menage.index', compact('pageTitle', 'menages','localites'));
    }
 
    public function create()
    {
        $pageTitle = "Ajouter un menage";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        return view('staff.menage.create', compact('pageTitle', 'producteurs','localites'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'producteur'    => 'required|exists:producteurs,id',
            'quartier' => 'required|max:255',
            'sources_energies'  => 'required|max:255',
            'ordures_menageres'  => 'required|max:255',
            'separationMenage'  => 'required|max:255', 
            'eauxToillette'  => 'required|max:255', 
            'eauxVaisselle'  => 'required|max:255', 
            'wc'  => 'required|max:255', 
            'sources_eaux'  => 'required|max:255', 
            'traitementChamps'  => 'required|max:255', 
            'equipements'  => 'required|max:255',
            'activiteFemme'  => 'required|max:255',
            'superficieCacaoFemme'  => 'required|max:255', 
        ];
 

        $request->validate($validationRule);

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
        $menage->sources_energies  = $request->sources_energies;
        $menage->boisChauffe     = $request->boisChauffe;
        $menage->ordures_menageres    = $request->ordures_menageres;
        $menage->separationMenage = $request->separationMenage; 
        $menage->eauxToillette    = $request->eauxToillette;
        $menage->eauxVaisselle    = $request->eauxVaisselle; 
        $menage->wc    = $request->wc; 
        $menage->sources_eaux    = $request->sources_eaux; 
        $menage->machine    = $request->machine; 
        $menage->type_machines    = $request->type_machines; 
        $menage->garde_machines    = $request->garde_machines; 
        $menage->equipements    = $request->equipements; 
        $menage->traitementChamps    = $request->traitementChamps; 
        $menage->nomPersonneTraitant    = $request->nomPersonneTraitant; 
        $menage->numeroPersonneTraitant    = $request->numeroPersonneTraitant; 
        $menage->empruntMachine    = $request->empruntMachine; 
        $menage->gardeEmpruntMachine    = $request->gardeEmpruntMachine; 
        $menage->activiteFemme    = $request->activiteFemme; 
        $menage->nomActiviteFemme    = $request->nomActiviteFemme; 
        $menage->superficieCacaoFemme    = $request->superficieCacaoFemme; 
        $menage->champFemme    = $request->champFemme; 
        $menage->nombreHectareFemme    = $request->nombreHectareFemme;
       
        $menage->save(); 

        $notify[] = ['success', isset($message) ? $message : 'Le menage a été crée avec succès.'];
        return back()->withNotify($notify);
    }
 

    public function edit($id)
    {
        $pageTitle = "Mise à jour de le menage";
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        $producteurs  = Producteur::with('localite')->get();
        $menage   = Menage::findOrFail($id);
        return view('staff.menage.edit', compact('pageTitle', 'localites', 'menage','producteurs'));
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
