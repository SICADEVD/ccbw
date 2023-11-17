<?php

namespace App\Http\Controllers\Manager;

use App\Constants\Status;
use App\Exports\ExportParcelles;
use App\Http\Controllers\Controller;
use App\Imports\ParcelleImport;
use App\Models\Localite; 
use App\Models\Producteur; 
use App\Models\Parcelle; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Excel;

class AgrodeforestationController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des parcelles";
        $manager   = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $parcelles = Parcelle::dateFilter()->searchable(['codeParc', 'typedeclaration','anneeCreation','culture'])->latest('id')->joinRelationship('parcelle.producteur.localite.section')->where('sections.cooperative_id',$manager->cooperative_id)->where(function ($q) {
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
        $producteurs  = Producteur::with('localite')->get();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->orderBy('nom')->get();
        return view('manager.parcelle.create', compact('pageTitle', 'producteurs','localites'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'producteur'    => 'required|exists:producteurs,id',
            'typedeclaration' => 'required|max:255',
            'anneeCreation'  => 'required|max:255',
            'culture'  => 'required|max:255',
            'superficie'  => 'required|max:255', 
        ];
 

        $request->validate($validationRule);

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
        
        $parcelle->producteur_id  = $request->producteur;  
        $parcelle->typedeclaration  = $request->typedeclaration;
        $parcelle->anneeCreation  = $request->anneeCreation;
        $parcelle->culture     = $request->culture;
        $parcelle->superficie    = $request->superficie;
        $parcelle->latitude = $request->latitude; 
        $parcelle->longitude    = $request->longitude; 
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
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
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
