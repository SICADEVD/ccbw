<?php

namespace App\Http\Controllers\Manager;

use Excel;
use App\Models\Campagne;
use App\Models\Localite;
use App\Models\Parcelle;
use App\Constants\Status;
use App\Models\Producteur;
use App\Models\Cooperative;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SuiviParcelle;
use App\Models\Agroespecesarbre;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SuiviParcellesAnimal;
use Illuminate\Support\Facades\Hash;
use App\Exports\ExportSuiviParcelles;
use App\Models\Suivi_parcelle_pesticide;
use App\Models\SuiviParcellesInsecte;
use App\Models\SuiviParcellesOmbrage;
use App\Models\SuiviParcellesParasite;
use App\Models\SuiviParcellesAgroforesterie;

class SuiviParcelleController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des suivi parcelles";
        $manager   = auth()->user();
        $cooperative = Cooperative::with('sections.localites', 'sections.localites.section')->find($manager->cooperative_id);
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $sections = $cooperative->sections;
        $suiviparcelles = SuiviParcelle::dateFilter()
            ->searchable(["varieteAbres", "nombreSauvageons", "arbresagroforestiers", "activiteTaille", "activiteEgourmandage", "activiteDesherbageManuel", "activiteRecolteSanitaire", "intrantNPK", "nombresacsNPK", "intrantFiente", "nombresacsFiente", "intrantComposte", "nombresacsComposte", "presencePourritureBrune", "presenceBioAgresseur", "presenceInsectesRavageurs", "presenceFourmisRouge", "presenceAraignee", "presenceVerTerre", "presenceMenteReligieuse", "presenceSwollenShoot", "presenceInsectesParasites", "nomInsecticide", "nombreInsecticide", "nomFongicide", "uniteFongicide", "nomHerbicide", "uniteHerbicide", "nombreDesherbage"])
            ->latest('id')
            ->joinRelationship('parcelle.producteur.localite')
            ->where(function ($q) {
                if (request()->localite != null) {
                    $q->where('localite.localite_id', request()->localite);
                }
            })
            ->with(['parcelle.producteur.localite'])
            ->where('suivi_parcelles.userid', $manager->id)
            ->paginate(getPaginate());


        return view('manager.suiviparcelle.index', compact('pageTitle', 'suiviparcelles', 'localites'));
    }

    public function create()
    {
        $pageTitle = "Ajouter un suivi parcelle";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $cooperative = Cooperative::with('sections.localites', 'sections.localites.section')->find($manager->cooperative_id);
        $sections = $cooperative->sections;
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $campagnes = Campagne::active()->pluck('nom', 'id');
        $parcelles  = Parcelle::with('producteur')->get();
        $arbres = Agroespecesarbre::all();
        return view('manager.suiviparcelle.create', compact('pageTitle', 'producteurs', 'localites', 'campagnes', 'parcelles', 'sections', 'arbres'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'parcelle_id'    => 'required|exists:parcelles,id',
            'campagne_id' => 'required|max:255',
            'dateVisite'  => 'required|max:255',
            'items.*.arbre'     => 'required|integer',
            'items.*.nombre'     => 'required|integer',
        ];

        $request->validate($validationRule);

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivée'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->id) {
            $suivi_parcelle = SuiviParcelle::findOrFail($request->id);
            $message = "Le suivi parcelle a été mise à jour avec succès";
        } else {
            $suivi_parcelle = new SuiviParcelle();
        }

        $suivi_parcelle->parcelle_id  = $request->parcelle_id;
        $suivi_parcelle->campagne_id  = $request->campagne_id;
        $suivi_parcelle->nombreSauvageons  = $request->nombreSauvageons;
        $suivi_parcelle->recuArbreAgroForestier  = $request->recuArbreAgroForestier;
        $suivi_parcelle->activiteTaille  = $request->activiteTaille;
        $suivi_parcelle->activiteEgourmandage = $request->activiteEgourmandage;
        $suivi_parcelle->activiteDesherbageManuel = $request->activiteDesherbageManuel;
        $suivi_parcelle->activiteRecolteSanitaire = $request->activiteRecolteSanitaire;
        $suivi_parcelle->activiteRecolteSanitaire = $request->activiteRecolteSanitaire;
        $suivi_parcelle->intrantNPK = $request->intrantNPK;
        $suivi_parcelle->nombresacsNPK = $request->nombresacsNPK;
        $suivi_parcelle->capaciteNPK = $request->capaciteNPK;
        $suivi_parcelle->conteneurNPK = $request->conteneurNPK;
        $suivi_parcelle->qteNPK = $request->qteNPK;

        $suivi_parcelle->intrantFiente = $request->intrantFiente;
        $suivi_parcelle->nombresacsFiente    = $request->nombresacsFiente;
        $suivi_parcelle->capaciteFiente    = $request->capaciteFiente;
        $suivi_parcelle->conteneurFiente    = $request->conteneurFiente;
        $suivi_parcelle->qteFiente    = $request->qteFiente;
        $suivi_parcelle->intrantComposte    = $request->intrantComposte;
        $suivi_parcelle->nombresacsComposte    = $request->nombresacsComposte;
        $suivi_parcelle->capaciteComposte    = $request->capaciteComposte;
        $suivi_parcelle->conteneurComposte    = $request->conteneurComposte;
        $suivi_parcelle->qteComposte    = $request->qteComposte;
        $suivi_parcelle->intrantDechetsAnimaux    = $request->intrantDechetsAnimaux;
        $suivi_parcelle->nombreDechetsAnimaux   = $request->nombreDechetsAnimaux;
        $suivi_parcelle->capaciteDechetsAnimaux   = $request->capaciteDechetsAnimaux;
        $suivi_parcelle->conteneurDechetsAnimaux   = $request->conteneurDechetsAnimaux;
        $suivi_parcelle->qteDechetsAnimaux   = $request->qteDechetsAnimaux;
        $suivi_parcelle->qteBiofertilisant    = $request->qteBiofertilisant;
        $suivi_parcelle->uniteBioFertilisant   = $request->uniteBioFertilisant;
        $suivi_parcelle->qteEngraisOrganique    = $request->qteEngraisOrganique;
        $suivi_parcelle->uniteEngraisOrganique   = $request->uniteEngraisOrganique;
        $suivi_parcelle->frequencePesticide   = $request->frequencePesticide;
        $suivi_parcelle->autrePesticide = $request->autrePesticide;
        $suivi_parcelle->presencePourritureBrune    = $request->presencePourritureBrune;
        $suivi_parcelle->presenceSwollenShoot    = $request->presenceSwollenShoot;
        $suivi_parcelle->presenceInsectesParasites    = $request->presenceInsectesParasites;
        // $suivi_parcelle->presenceInsectesParasitesRavageur    = $request->presenceInsectesParasitesRavageur;
        $suivi_parcelle->presenceBioAgresseur    = $request->presenceBioAgresseur;
        $suivi_parcelle->presenceInsectesRavageurs    = $request->presenceInsectesRavageurs;
        $suivi_parcelle->presenceFourmisRouge    = $request->presenceFourmisRouge;
        $suivi_parcelle->presenceAraignee    = $request->presenceAraignee;
        $suivi_parcelle->presenceVerTerre    = $request->presenceVerTerre;
        $suivi_parcelle->presenceMenteReligieuse    = $request->presenceMenteReligieuse;
        $suivi_parcelle->nomInsecticide    = $request->nomInsecticide;
        $suivi_parcelle->nombreInsecticide    = $request->nombreInsecticide;
        $suivi_parcelle->nomFongicide    = $request->nomFongicide;
        $suivi_parcelle->uniteFongicide    = $request->uniteFongicide;
        $suivi_parcelle->qteFongicide    = $request->qteFongicide;
        $suivi_parcelle->qteHerbicide    = $request->qteHerbicide;
        $suivi_parcelle->nomHerbicide    = $request->nomHerbicide;
        $suivi_parcelle->uniteHerbicide    = $request->uniteHerbicide;
        $suivi_parcelle->nombreDesherbage    = $request->nombreDesherbage;
        $suivi_parcelle->presenceFourmisRouge   = $request->presenceFourmisRouge;
        $suivi_parcelle->presenceAraignee   = $request->presenceAraignee;
        $suivi_parcelle->presenceVerTerre   = $request->presenceVerTerre;
        $suivi_parcelle->presenceMenteReligieuse   = $request->presenceMenteReligieuse;
        $suivi_parcelle->arbresagroforestiers  = $request->arbresagroforestiers;
        $suivi_parcelle->dateVisite    = $request->dateVisite;
        $suivi_parcelle->presenceAutreTypeInsecteAmi    = $request->presenceAutreTypeInsecteAmi;
        $suivi_parcelle->userid   = auth()->user()->id;
        //dd(json_encode($request->all()));
        $suivi_parcelle->save();
        if ($suivi_parcelle != null) {
            $id = $suivi_parcelle->id;
            $datas = [];
            $datas2 = [];
            $datas3 = [];
            $datas4 = [];
            $datas5 = [];
            //arbre d'ombrage souhaite tu avoir
            if (($request->arbre != null)) {
                SuiviParcellesOmbrage::where('suivi_parcelle_id', $id)->delete();
                $i = 0;
                foreach ($request->arbre as $data) {
                    if ($data != null) {
                        $datas[] = [
                            'suivi_parcelle_id' => $id,
                            'agroespecesarbre_id' => $data,
                        ];
                    }
                    $i++;
                }
            }
            //fin arbre d'ombrage souhaite tu avoir

            //les arbres agro-forestiers obtenus
            if (($request->items != null)) {
                SuiviParcellesAgroforesterie::where('suivi_parcelle_id', $id)->delete();
                foreach ($request->items as $item) {

                    $data2[] = [
                        'suivi_parcelle_id' => $id,
                        'nombre' => $item['nombre'],
                        'agroespeceabre_id' => $item['arbre'],
                    ];
                }
            }
            //fin les arbres agro-forestiers obtenus

            //autres insectes parasites ou ravageurs
            if (($request->insectesParasites != null)) {
                SuiviParcellesParasite::where('suivi_parcelle_id', $id)->delete();
                $i = 0;
                foreach ($request->insectesParasites as $data) {
                    if ($data != null) {
                        $datas3[] = [
                            'suivi_parcelle_id' => $id,
                            'parasite' => $data,
                            'nombre' => $request->nombreinsectesParasites[$i]
                        ];
                    }
                    $i++;
                }
            }
            //fin autres insectes parasites ou ravageurs

            //insectes amis
            if (($request->insectesParasites != null)) {
                SuiviParcellesInsecte::where('suivi_parcelle_id', $id)->delete();
                $i = 0;
                foreach ($request->insectesAmis as $data) {
                    if ($data != null) {
                        $datas5[] = [
                            'suivi_parcelle_id' => $id,
                            'insecte' => $data,
                            'nombre' => $request->nombreinsectesAmis[$i]
                        ];
                    }
                    $i++;
                }
            }
            //fin insectes amis


            if (($request->animauxRencontres != null)) {
                SuiviParcellesAnimal::where('suivi_parcelle_id', $id)->delete();
                $i = 0;
                foreach ($request->animauxRencontres as $data) {
                    if ($data != null) {
                        $datas4[] = [
                            'suivi_parcelle_id' => $id,
                            'animal' => $data
                        ];
                    }
                    $i++;
                }
            }
            SuiviParcellesAnimal::insert($datas4);
            SuiviParcellesAgroforesterie::insert($data2);
            SuiviParcellesParasite::insert($datas3);
            SuiviParcellesInsecte::insert($datas5);
            SuiviParcellesOmbrage::insert($datas);

            if($request->pesticideUtiliseAnne != null){
                Suivi_parcelle_pesticide::where('suivi_parcelle_id', $id)->delete();
                $i = 0;
                foreach ($request->pesticideUtiliseAnne as $data) {
                    DB::table('suivi_parcelle_pesticides')->insert([
                        'suivi_parcelle_id' => $id,
                        'pesticide' => $data,
                    ]);
                    
                    $i++;
                }
            }
        }

        $notify[] = ['success', isset($message) ? $message : "Le suivi parcelle a été crée avec succès."];
        return back()->withNotify($notify);
    }



    public function edit($id)
    {
        $pageTitle = "Mise à jour du suivi parcelle";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $cooperative = Cooperative::with('sections.localites', 'sections.localites.section')->find($manager->cooperative_id);
        $sections = $cooperative->sections;
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $campagnes = Campagne::active()->pluck('nom', 'id');
        $parcelles  = Parcelle::with('producteur')->get();
        $suiviparcelle   = SuiviParcelle::findOrFail($id);
        $arbres = Agroespecesarbre::all();
        $arbreAgroForestiers = SuiviParcellesAgroforesterie::where('suivi_parcelle_id', $id)->get();
        $arbreOmbrages = SuiviParcellesOmbrage::where('suivi_parcelle_id', $id)->pluck('agroespecesarbre_id')->toArray();
        $parasites = SuiviParcellesParasite::where('suivi_parcelle_id', $id)->get();

        return view('manager.suiviparcelle.edit', compact('pageTitle', 'suiviparcelle', 'producteurs', 'localites', 'campagnes', 'parcelles', 'sections', 'arbres', 'arbreOmbrages', 'arbreAgroForestiers', 'parasites'));
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
