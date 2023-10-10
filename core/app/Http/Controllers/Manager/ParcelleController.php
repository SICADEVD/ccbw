<?php

namespace App\Http\Controllers\Manager;

use Excel;
use App\Constants\Status;
use App\Models\Localite; 
use App\Models\Parcelle; 
use App\Models\Cooperative;
use App\Models\Producteur; 
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\ParcelleImport;
use App\Exports\ExportParcelles;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ParcelleController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des parcelles";
        $manager   = auth()->user();
        // $localites = Localite::active()->where('cooperative_id',$manager->cooperative_id)->get();
        $cooperative = Cooperative::with('sections.localites')->find($manager->cooperative_id);
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $parcelles = Parcelle::dateFilter()->searchable(['codeParc'])->latest('id')->joinRelationship('producteur.localite.section')->where('cooperative_id',$manager->cooperative_id)->where(function ($q) {
            if(request()->localite != null){
                $q->where('localite_id',request()->localite);
            }
        })->with('producteur')->paginate(getPaginate());
         
        return view('manager.parcelle.index', compact('pageTitle', 'parcelles','localites'));
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
        return view('manager.parcelle.create', compact('pageTitle', 'producteurs','localites','sections'));
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
            'anneeRegenerer'=>'required_if:parcelleRegenerer,==,oui',
            'superficieRegenerer'=>'required_if:parcelleRegenerer,==,oui',
            'typeDoc'=>'required',
            'presenceCourDeau'=>'required',
            'courDeau'=>'required_if:presenceCourDeau,==,oui',
            'existeMesureProtection'=>'required',
            'existePente'=>'required',
            'superficie'=>'required',
            'nbCacaoParHectare'=>'required|numeric',
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
        ];
        $attributes = [
            'section' => 'section',
            'localite' => 'localité',
            'producteur_id' => 'producteur',
            'anneeCreation' => 'année de création',
            'ageMoyenCacao' => 'age moyen du cacao',
            'parcelleRegenerer' => 'parcelle regénéré',
            'anneeRegenerer'=>'L\'année de régénération',
            'superficieRegenerer'=>'superficie de régénérer',
            'typeDoc'=>'type de document',
            'presenceCourDeau'=>'présence de cours d\'eau',
            'courDeau'=>'cours d\'eau',
            'existeMesureProtection'=>'existence de mesure de protection',
            'existePente'=>'existence de pente',
            'superficie'=>'superficie',
            'nbCacaoParHectare'=>'nombre de cacao par hectare',
        ];
        $request->validate($validationRule, $messages, $attributes);

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivé'];
            return back()->withNotify($notify)->withInput();
        }
        
        if($request->id) {
            $parcelle = Parcelle::findOrFail($request->id);
            $codeParc=$parcelle->codeParc;
            if($codeParc ==''){
                $produc=Producteur::select('codeProdapp')->find($request->producteur);
            if($produc !=null){
            $codeProd = $produc->codeProdapp;
            }else{
            $codeProd='';
            }
            $parcelle->codeParc  =  $this->generecodeparc($request->producteur, $codeProd);
            }
            $message = "La parcelle a été mise à jour avec succès";

        } else {
            $parcelle = new Parcelle(); 
            $produc=Producteur::select('codeProdapp')->find($request->producteur);
            if($produc !=null){
            $codeProd = $produc->codeProdapp;
            }else{
            $codeProd='';
            }
            $parcelle->codeParc  =  $this->generecodeparc($request->producteur, $codeProd);
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
        $parcelle->Longitude  = $request->Longitude;
        $parcelle->userid = auth()->user()->id;
        $parcelle->nbCacaoParHectare  = $request->nbCacaoParHectare;
        

        if($request->hasFile('fichier_kml_gpx')) {
            try {
                $parcelle->fichier_kml_gpx = $request->file('fichier_kml_gpx')->store('public/parcelles/kmlgpx');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        } 

        $parcelle->save(); 

        $notify[] = ['success', isset($message) ? $message : 'Le parcelle a été crée avec succès.'];
        return back()->withNotify($notify);
    }
    private function generecodeparc($idProd,$codeProd)
    { 
      if($codeProd)
      {
        $action = 'non'; 

        $data = Parcelle::select('codeParc')->where([ 
          ['producteur_id',$idProd],
          ['codeParc','!=',null]
          ])->orderby('id','desc')->first();
          
        if($data !=''){
         
            $code = $data->codeParc;  
            
            if($code !=''){
              $chaine_number = Str::afterLast($code,'-');
        $numero = Str::after($chaine_number, 'P');
        $numero = $numero+1;
            }else{
              $numero = 1;
            } 
        $codeParc=$codeProd.'-P'.$numero;

        do{

          $verif = Parcelle::select('codeParc')->where('codeParc',$codeParc)->orderby('id','desc')->first(); 
        if($verif ==null){
            $action = 'non';
        }else{
            $action = 'oui';
            $code = $data->codeParc;  
            
            if($code !=''){
              $chaine_number = Str::afterLast($code,'-');
        $numero = Str::after($chaine_number, 'P');
        $numero = $numero+1;
            }else{
              $numero = 1;
            } 
        $codeParc=$codeProd.'-P'.$numero;

        }

    }while($action !='non');

        }else{ 
            $codeParc=$codeProd.'-P1';
        }
      }
      else{
        $codeParc='';
      }

        return $codeParc;
    }


    public function edit($id)
    {
        $pageTitle = "Mise à jour de la parcelle";
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        $producteurs  = Producteur::with('localite')->get();
        $parcelle   = Parcelle::findOrFail($id);
        return view('manager.parcelle.edit', compact('pageTitle', 'localites', 'parcelle','producteurs'));
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
