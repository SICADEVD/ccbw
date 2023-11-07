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
use App\Models\LivraisonPrime;
use App\Imports\ParcelleImport;
use App\Models\LivraisonScelle;
use App\Exports\ExportParcelles;
use App\Models\Agroespecesarbre;
use App\Models\LivraisonProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\agroespeceabre_parcelle;
use App\Models\Parcelle_type_protection;
use App\Models\Producteur_infos_typeculture;

class ParcelleController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des parcelles";
        $manager   = auth()->user();
        // $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $cooperative = Cooperative::with('sections.localites')->find($manager->cooperative_id);
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        // $parcelles = Parcelle::dateFilter()->searchable(['codeParc'])->latest('id')->joinRelationship('producteur.localite.section')->where('cooperative_id',$manager->cooperative_id)->where(function ($q) {
        //     if(request()->localite != null){
        //         $q->where('localite_id',request()->localite);
        //     }
        // })->with('producteur')->paginate(getPaginate());
        $parcelles = Parcelle::dateFilter()->searchable(['codeParc'])
            ->latest('id')
            ->joinRelationship('producteur.localite.section')
            ->where('cooperative_id', $manager->cooperative_id)
            ->where(function ($q) {
                if (request()->localite != null) {
                    $q->where('localite_id', request()->localite);
                }
            })
            ->with(['producteur.localite.section']) // Charger les relations nécessaires
            ->paginate(getPaginate());


        return view('manager.parcelle.index', compact('pageTitle', 'parcelles', 'localites'));
    }

    public function create()
    {
        $pageTitle = "Ajouter un parcelle";
        $manager   = auth()->user();
        $cooperative = Cooperative::with('sections.localites', 'sections.localites.section')->find($manager->cooperative_id);
        $sections = $cooperative->sections;
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $producteurs  = Producteur::with('localite')->get();
        $arbres = Agroespecesarbre::all();
        return view('manager.parcelle.create', compact('pageTitle', 'producteurs', 'localites', 'sections', 'arbres'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'section' => 'required',
            'localite' => 'required',
            'producteur_id' => 'required',
            'anneeCreation' => 'required',
            'ageMoyenCacao' => 'required',
            'parcelleRegenerer' => 'required',
            'anneeRegenerer' => 'required_if:parcelleRegenerer,==,oui',
            'superficieConcerne' => 'required_if:parcelleRegenerer,==,oui',
            'typeDoc' => 'required',
            'presenceCourDeau' => 'required',
            'courDeau' => 'required_if:presenceCourDeau,==,oui',
            'autreCourDeau' => 'required_if:courDeau,==,Autre',
            'existeMesureProtection' => 'required',
            'existePente' => 'required',
            'superficie' => 'required',
            'nbCacaoParHectare' => 'required|numeric',
            'erosion' => 'required',
            'items.*.arbre'     => 'required|integer',
            'items.*.nombre'     => 'required|integer',
            'longitude' => 'numeric|nullable',
            'latitude' => 'numeric|nullable',
        ];
        $messages = [
            'section.required' => 'Le champ section est obligatoire',
            'localite.required' => 'Le champ localité est obligatoire',
            'producteur_id.required' => 'Le champ producteur est obligatoire',
            'anneeCreation.required' => 'Le champ année de création est obligatoire',
            'ageMoyenCacao.required' => 'Le champ age moyen du cacao est obligatoire',
            'parcelleRegenerer.required' => 'Le champ parcelle à regénérer est obligatoire',
            'anneeRegenerer.required_if' => 'Le champ année de régénération est obligatoire',
            'superficieRegenerer.required_if' => 'Le champ superficie de régénération est obligatoire',
            'typeDoc.required' => 'Le champ type de document est obligatoire',
            'presenceCourDeau.required' => 'Le champ présence de cours d\'eau est obligatoire',
            'courDeau.required_if' => 'Le champ cours d\'eau est obligatoire',
            'existeMesureProtection.required' => 'Le champ existence de mesure de protection est obligatoire',
            'existePente.required' => 'Le champ existence de pente est obligatoire',
            'superficie.required' => 'Le champ superficie est obligatoire',
            'nbCacaoParHectare.required' => 'Le champ nombre de cacao par hectare est obligatoire',
            'superficieConcerne.required_if' => 'Le champ superficie concerné est obligatoire',
            'erosion.required' => 'Le champ érosion est obligatoire',
            'longitude.numeric' => 'Le champ longitude doit être un nombre décimal',
            'latitude.numeric' => 'Le champ latitude doit être un nombre décimal',
        ];
        $attributes = [
            'section' => 'section',
            'localite' => 'localité',
            'producteur_id' => 'producteur',
            'anneeCreation' => 'année de création',
            'ageMoyenCacao' => 'age moyen du cacao',
            'parcelleRegenerer' => 'parcelle regénéré',
            'anneeRegenerer' => 'L\'année de régénération',
            'superficieRegenerer' => 'superficie de régénérer',
            'typeDoc' => 'type de document',
            'presenceCourDeau' => 'présence de cours d\'eau',
            'courDeau' => 'cours d\'eau',
            'existeMesureProtection' => 'existence de mesure de protection',
            'existePente' => 'existence de pente',
            'superficie' => 'superficie',
            'nbCacaoParHectare' => 'nombre de cacao par hectare',
            'superficieConcerne' => 'superficie concerné',
            'erosion' => 'érosion',
        ];
        $request->validate($validationRule, $messages, $attributes);
        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivé'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->id) {
            $parcelle = Parcelle::findOrFail($request->id);
            $codeParc = $parcelle->codeParc;
            if ($codeParc == '') {
                $produc = Producteur::select('codeProdapp')->find($request->producteur_id);
                if ($produc != null) {
                    $codeProd = $produc->codeProdapp;
                } else {
                    $codeProd = '';
                }
                $parcelle->codeParc  =  $this->generecodeparc($request->producteur_id, $codeProd);
            }
            $message = "La parcelle a été mise à jour avec succès";
        } else {
            $parcelle = new Parcelle();
            $produc = Producteur::select('codeProdapp')->find($request->producteur_id);
            if ($produc != null) {
                $codeProd = $produc->codeProdapp;
            } else {
                $codeProd = '';
            }
            $parcelle->codeParc  =  $this->generecodeparc($request->producteur_id, $codeProd);
        }
        $parcelle->producteur_id  = $request->producteur_id;
        $parcelle->niveauPente  = $request->niveauPente;
        $parcelle->anneeCreation  = $request->anneeCreation;
        $parcelle->ageMoyenCacao  = $request->ageMoyenCacao;
        $parcelle->parcelleRegenerer  = $request->parcelleRegenerer;
        $parcelle->anneeRegenerer  = $request->anneeRegenerer;
        $parcelle->superficieConcerne  = $request->superficieConcerne;
        $parcelle->typeDoc  = $request->typeDoc;
        $parcelle->presenceCourDeau  = $request->presenceCourDeau;
        $parcelle->courDeau  = $request->courDeau;
        $parcelle->existeMesureProtection  = $request->existeMesureProtection;
        $parcelle->existePente  = $request->existePente;
        $parcelle->superficie  = $request->superficie;
        $parcelle->latitude  = $request->latitude;
        $parcelle->longitude  = $request->longitude;
        $parcelle->userid = auth()->user()->id;
        $parcelle->nbCacaoParHectare  = $request->nbCacaoParHectare;
        $parcelle->erosion  = $request->erosion;
        $parcelle->autreCourDeau = $request->autreCourDeau;
        $parcelle->autreProtection = $request->autreProtection;


        if ($request->hasFile('fichier_kml_gpx')) {
            try {
                $parcelle->fichier_kml_gpx = $request->file('fichier_kml_gpx')->store('public/parcelles/kmlgpx');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        }
        //  dd(json_encode($request->all()));
        $parcelle->save();
        if ($parcelle != null) {
            $id = $parcelle->id;
            $datas  = $data2 = [];
            if (($request->protection != null)) {
                Parcelle_type_protection::where('parcelle_id', $id)->delete();
                $i = 0;
                foreach ($request->protection as $protection) {
                    if (!empty($protection)) { 
                        $datas[] = [
                            'parcelle_id' => $id,
                            'typeProtection' => $protection,
                        ];
                    }
                    
                    $i++;
                }
            }
            if (($request->items != null)) {
                agroespeceabre_parcelle::where('parcelle_id', $id)->delete();
                foreach ($request->items as $item) {

                    $data2[] = [
                        'parcelle_id' => $id,
                        'nombre' => $item['nombre'],
                        'agroespeceabre_id' => $item['arbre'],
                    ];
                }
            }

            Parcelle_type_protection::insert($datas);
            agroespeceabre_parcelle::insert($data2);
        }


        $notify[] = ['success', isset($message) ? $message : 'Le parcelle a été crée avec succès.'];
        return back()->withNotify($notify);
    }
    private function generecodeparc($idProd, $codeProd)
    {
        if ($codeProd) {
            $action = 'non';

            $data = Parcelle::select('codeParc')->where([
                ['producteur_id', $idProd],
                ['codeParc', '!=', null]
            ])->orderby('id', 'desc')->first();

            if ($data != '') {

                $code = $data->codeParc;

                if ($code != '') {
                    $chaine_number = Str::afterLast($code, '-');
                    $numero = Str::after($chaine_number, 'P');
                    $numero = $numero + 1;
                } else {
                    $numero = 1;
                }
                $codeParc = $codeProd . '-P' . $numero;

                do {

                    $verif = Parcelle::select('codeParc')->where('codeParc', $codeParc)->orderby('id', 'desc')->first();
                    if ($verif == null) {
                        $action = 'non';
                    } else {
                        $action = 'oui';
                        $code = $data->codeParc;

                        if ($code != '') {
                            $chaine_number = Str::afterLast($code, '-');
                            $numero = Str::after($chaine_number, 'P');
                            $numero = $numero + 1;
                        } else {
                            $numero = 1;
                        }
                        $codeParc = $codeProd . '-P' . $numero;
                    }
                } while ($action != 'non');
            } else {
                $codeParc = $codeProd . '-P1';
            }
        } else {
            $codeParc = '';
        }

        return $codeParc;
    }


    public function edit($id)
    {
        $pageTitle = "Mise à jour de la parcelle";
        $parcelle   = Parcelle::findOrFail($id);
        $manager   = auth()->user();
        $cooperative = Cooperative::with('sections.localites', 'sections.localites.section')->find($manager->cooperative_id);
        $sections = $cooperative->sections;
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $producteurs  = Producteur::with('localite')->get();
        $protections = $parcelle->parcelleTypeProtections->pluck('typeProtection')->all();
        $arbres = Agroespecesarbre::all();
        $agroespeceabreParcelle = agroespeceabre_parcelle::where('parcelle_id', $id)->get();

        return view('manager.parcelle.edit', compact('pageTitle', 'localites', 'parcelle', 'producteurs', 'sections', 'protections', 'arbres', 'agroespeceabreParcelle'));

        // $protections = Parcelle_type_protection::where('parcelle_id',$id)->get();

        // $protections = Parcelle_type_protection::where('parcelle_id', $id)->get()->map(function ($protection) {
        //     return $protection->typeProtection;
        // });
    }

    public function status($id)
    {
        return Parcelle::changeStatus($id);
    }

    public function exportExcel()
    {
        return (new ExportParcelles())->download('parcelles.xlsx');
    }

    public function  uploadContent(Request $request)
    {
        Excel::import(new ParcelleImport, $request->file('uploaded_file'));
        return back();
    }
}
