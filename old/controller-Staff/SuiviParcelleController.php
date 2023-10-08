<?php

namespace App\Http\Controllers\Staff;

use App\Constants\Status;
use App\Exports\ExportSuiviParcelles;
use App\Http\Controllers\Controller;
use App\Models\Localite; 
use App\Models\Producteur; 
use App\Models\SuiviParcelle; 
use App\Models\Parcelle; 
use App\Models\Campagne;
use App\Models\SuiviParcellesAgroforesterie;
use App\Models\SuiviParcellesAnimal;
use App\Models\SuiviParcellesOmbrage;
use App\Models\SuiviParcellesParasite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Excel;

class SuiviParcelleController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des suivi parcelles";
        $manager   = auth()->user();
        $localites = Localite::active()->where('cooperative_id',$manager->cooperative_id)->get();
        $suiviparcelles = SuiviParcelle::dateFilter()->searchable(["varietes_cacao","autreVariete","existeCoursEaux","cours_eaux","pente","varieteAbres","nombreSauvageons","arbresagroforestiers","activiteTaille","activiteEgourmandage","activiteDesherbageManuel","activiteRecolteSanitaire","intrantNPK","nombresacsNPK","intrantFiente","nombresacsFiente","intrantComposte","nombresacsComposte","presencePourritureBrune","presenceBioAgresseur","presenceInsectesRavageurs","presenceFourmisRouge","presenceAraignee","presenceVerTerre","presenceMenteReligieuse","presenceSwollenShoot","presenceInsectesParasites","nomInsecticide","nombreInsecticide","nomFongicide","nombreFongicide","nomHerbicide","nombreHerbicide","nombreDesherbage"])->latest('id')->joinRelationship('parcelle.producteur.localite')->where('cooperative_id',$manager->cooperative_id)->where(function ($q) {
            if(request()->localite != null){
                $q->where('localite_id',request()->localite);
            }
        })->with('parcelle')->paginate(getPaginate());
         
        return view('staff.suiviparcelle.index', compact('pageTitle', 'suiviparcelles','localites'));
    }
 
    public function create()
    {
        $pageTitle = "Ajouter un suivi parcelle";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        $campagnes = Campagne::active()->pluck('nom','id');
        $parcelles  = Parcelle::with('producteur')->get();
        return view('staff.suiviparcelle.create', compact('pageTitle', 'producteurs','localites','campagnes','parcelles'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'parcelle'    => 'required|exists:parcelles,id',
            'campagne' => 'required|max:255',  
            'dateVisite'  => 'required|max:255', 
        ];
 

        $request->validate($validationRule);

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivée'];
            return back()->withNotify($notify)->withInput();
        }
        
        if($request->id) {
            $suivi_parcelle = SuiviParcelle::findOrFail($request->id);
            $message = "Le suivi parcelle a été mise à jour avec succès";
        } else {
            $suivi_parcelle = new SuiviParcelle();  
        } 
        
        $suivi_parcelle->parcelle_id  = $request->parcelle;  
        $suivi_parcelle->campagne_id  = $request->campagne;
        $suivi_parcelle->varietes_cacao  = $request->varietes_cacao;
        $suivi_parcelle->autreVariete  = $request->autreVariete;
        $suivi_parcelle->existeCoursEaux  = $request->existeCoursEaux;
        $suivi_parcelle->cours_eaux  = $request->cours_eaux;
        $suivi_parcelle->pente  = $request->pente;
        $suivi_parcelle->varieteAbres  = $request->varieteAbres;
        $suivi_parcelle->nombreSauvageons  = $request->nombreSauvageons;
        $suivi_parcelle->arbresagroforestiers  = $request->arbresagroforestiers;
        $suivi_parcelle->activiteTaille  = $request->activiteTaille;
        $suivi_parcelle->activiteEgourmandage = $request->activiteEgourmandage; 
        $suivi_parcelle->activiteDesherbageManuel = $request->activiteDesherbageManuel;
        $suivi_parcelle->activiteRecolteSanitaire = $request->activiteRecolteSanitaire;
        $suivi_parcelle->intrantNPK = $request->intrantNPK;  
        $suivi_parcelle->nombresacsNPK = $request->nombresacsNPK; 
        $suivi_parcelle->intrantFiente = $request->intrantFiente; 
        $suivi_parcelle->nombresacsFiente    = $request->nombresacsFiente;
        $suivi_parcelle->intrantComposte    = $request->intrantComposte;
        $suivi_parcelle->nombresacsComposte    = $request->nombresacsComposte;
        $suivi_parcelle->presencePourritureBrune    = $request->presencePourritureBrune;
        $suivi_parcelle->presenceBioAgresseur    = $request->presenceBioAgresseur;
        $suivi_parcelle->presenceInsectesRavageurs    = $request->presenceInsectesRavageurs;
        $suivi_parcelle->presenceFourmisRouge    = $request->presenceFourmisRouge; 
        $suivi_parcelle->presenceAraignee    = $request->presenceAraignee; 
        $suivi_parcelle->presenceVerTerre    = $request->presenceVerTerre; 
        $suivi_parcelle->presenceMenteReligieuse    = $request->presenceMenteReligieuse; 
        $suivi_parcelle->presenceSwollenShoot    = $request->presenceSwollenShoot; 
        $suivi_parcelle->presenceInsectesParasites    = $request->presenceInsectesParasites; 
        $suivi_parcelle->nomInsecticide    = $request->nomInsecticide; 
        $suivi_parcelle->nombreInsecticide    = $request->nombreInsecticide; 
        $suivi_parcelle->nomFongicide    = $request->nomFongicide; 
        $suivi_parcelle->nombreFongicide    = $request->nombreFongicide; 
        $suivi_parcelle->nomHerbicide    = $request->nomHerbicide; 
        $suivi_parcelle->nombreHerbicide    = $request->nombreHerbicide; 
        $suivi_parcelle->nombreDesherbage    = $request->nombreDesherbage; 
        $suivi_parcelle->dateVisite    = $request->dateVisite;  

        $suivi_parcelle->save(); 
        if($suivi_parcelle !=null ){
            $id = $suivi_parcelle->id;
            $datas = [];
            $datas2 = [];
            $datas3 = [];
            $datas4 = [];
            if(($request->varietesOmbrage !=null)) { 
                SuiviParcellesOmbrage::where('suivi_parcelle_id',$id)->delete();
                $i=0; 
                foreach($request->varietesOmbrage as $data){
                    if($data !=null)
                    {
                        $datas[] = [
                        'suivi_parcelle_id' => $id, 
                        'ombrage' => $data, 
                        'nombre' => $request->nombreOmbrage[$i]
                    ];
                    } 
                  $i++;
                } 
            }

            if(($request->agroforestiers !=null)) { 
                SuiviParcellesAgroforesterie::where('suivi_parcelle_id',$id)->delete();
                $i=0; 
                foreach($request->agroforestiers as $data){
                    if($data !=null)
                    {
                        $datas2[] = [
                        'suivi_parcelle_id' => $id, 
                        'agroforesterie' => $data, 
                        'nombre' => $request->nombreagroforestiers[$i]
                    ];
                    } 
                  $i++;
                } 
            }
            if(($request->insectesParasites !=null)) { 
                SuiviParcellesParasite::where('suivi_parcelle_id',$id)->delete();
                $i=0; 
                foreach($request->insectesParasites as $data){
                    if($data !=null)
                    {
                        $datas3[] = [
                            'suivi_parcelle_id' => $id, 
                            'parasite' => $data, 
                            'nombre' => $request->nombreinsectesParasites[$i]
                        ];
                    } 
                  $i++;
                } 
            }
            if(($request->animauxRencontres !=null)) { 
                SuiviParcellesAnimal::where('suivi_parcelle_id',$id)->delete();
                $i=0; 
                foreach($request->animauxRencontres as $data){
                    if($data !=null)
                    {
                        $datas4[] = [
                            'suivi_parcelle_id' => $id, 
                            'animal' => $data
                        ];
                    } 
                  $i++;
                } 
            }

        SuiviParcellesAnimal::insert($datas4);
        SuiviParcellesParasite::insert($datas3);
        SuiviParcellesAgroforesterie::insert($datas2); 
        SuiviParcellesOmbrage::insert($datas);
        }

        $notify[] = ['success', isset($message) ? $message : "Le suivi parcelle a été crée avec succès."];
        return back()->withNotify($notify);
    }

     

    public function edit($id)
    {
        $pageTitle = "Mise à jour du suivi parcelle";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        $campagnes = Campagne::active()->pluck('nom','id');
        $parcelles  = Parcelle::with('producteur')->get();
        $suiviparcelle   = SuiviParcelle::findOrFail($id);
        return view('staff.suiviparcelle.edit', compact('pageTitle', 'suiviparcelle','producteurs','localites','campagnes','parcelles'));
    } 

    public function statusSuiviParc($id)
    {
        return SuiviParcelle::changeStatus($id);
    }

    public function exportExcel()
    { 
        $filename = 'suiviparcelles-' . gmdate('dmYhms') . '.xlsx';
        return Excel::download(new ExportSuiviParcelles, $filename);
    }

}
