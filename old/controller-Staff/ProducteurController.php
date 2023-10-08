<?php

namespace App\Http\Controllers\Staff;

use App\Constants\Status;
use App\Exports\ExportProducteurs;
use App\Http\Controllers\Controller;
use App\Imports\ProducteurImport;
use App\Models\Cooperative;
use App\Models\Localite; 
use App\Models\Producteur;
use App\Models\Producteur_info;
use App\Models\Producteur_infos_maladieenfant;
use App\Models\Producteur_infos_typeculture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Excel;

class ProducteurController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des producteurs";
        $manager   = auth()->user();
        $cooperatives = Cooperative::active()->get();
        $localites = Localite::active()->where('cooperative_id',$manager->cooperative_id)->get();
        $producteurs = Producteur::dateFilter()->searchable(["nationalite","type_piece","codeProd","codeProdapp","producteurs.nom","prenoms","sexe","dateNaiss","phone1","phone2","niveau_etude","numPiece","consentement","statut","certificat"])->latest('id')->joinRelationship('localite')->where('cooperative_id',$manager->cooperative_id)->where(function ($q) {
            if(request()->localite != null){
                $q->where('localite_id',request()->localite);
            }
            if(request()->status != null){
                $q->where('statut',request()->status);
            }
        })->with('localite')->paginate(getPaginate());
        
        return view('staff.producteur.index', compact('pageTitle', 'producteurs','localites','cooperatives'));
    }

    public function infos($id)
    {
        
        $pageTitle = "Gestion des informations du producteur";
        $infosproducteurs = Producteur_info::all()->where('producteur_id',decrypt($id));
        
        return view('staff.producteur.infos', compact('pageTitle', 'infosproducteurs','id'));
    }

    public function editinfo($id)
    {
        
        $pageTitle      = "Gestion des informations du producteur";
        $infosproducteur = Producteur_info::findOrFail(decrypt($id)); 
        
        return view('staff.producteur.editinfo', compact('pageTitle', 'infosproducteur','id'));
    }

    public function create()
    {
        $pageTitle = "Ajouter un producteur";
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        return view('staff.producteur.create', compact('pageTitle', 'localites'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'localite'    => 'required|exists:localites,id',
            'nom' => 'required|max:255',
            'prenoms'  => 'required|max:255',
            'sexe'  => 'required|max:255',
            'nationalite'  => 'required|max:255',
            'dateNaiss'  => 'required|max:255',
            'phone1'  => 'required|max:255',
            'niveau_etude'  => 'required|max:255',
            'type_piece'  => 'required|max:255',
            'numPiece'  => 'required|max:255',
        ];
 

        $request->validate($validationRule);

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivé'];
            return back()->withNotify($notify)->withInput();
        }
        
        if($request->id) {
            $producteur = Producteur::findOrFail($request->id);
            if($producteur->codeProdapp==null){
                $coop = DB::table('localites as l')->join('cooperatives as c','l.cooperative_id','=','c.id')->where('l.id',$request->localite)->select('c.codeApp')->first();
            if($coop !=null)
            { 
            $producteur->codeProdapp = $this->generecodeProdApp($request->nom,$request->prenoms, $coop->codeApp);

            }else{
            $producteur->codeProdapp = null;
            }
            }
            $message = "La producteur a été mise à jour avec succès";
        } else {
            $producteur           = new Producteur(); 
            $coop = DB::table('localites as l')->join('cooperatives as c','l.cooperative_id','=','c.id')->where('l.id',$request->localite)->select('c.codeApp')->first();
            if($coop !=null)
            { 
            $producteur->codeProdapp = $this->generecodeProdApp($request->nom,$request->prenoms, $coop->codeApp);

            }else{
            $producteur->codeProdapp = null;
            }
        } 
        
         
        $producteur->localite_id = $request->localite; 
        $producteur->consentement  = $request->consentement;
        $producteur->statut  = $request->statut;
        $producteur->certificat     = $request->certificat;
        $producteur->codeProd    = $request->codeProd;
        $producteur->nom = $request->nom;
        $producteur->prenoms    = $request->prenoms;
        $producteur->sexe    = $request->sexe;
        $producteur->nationalite    = $request->nationalite;
        $producteur->dateNaiss    = $request->dateNaiss;
        $producteur->phone1    = $request->phone1;
        $producteur->phone2    = $request->phone2;
        $producteur->niveau_etude    = $request->niveau_etude;
        $producteur->type_piece    = $request->type_piece;
        $producteur->numPiece    = $request->numPiece;

        if($request->hasFile('copiecarterecto')) {
            
            try {
                //$old = $producteur->copiecarterecto ?: null;
                
                //$producteur->copiecarterecto = fileUploader($request->copiecarterecto, getFilePath('copiecarterecto'), getFileSize('copiecarterecto'), $old);
                $producteur->copiecarterecto = $request->file('copiecarterecto')->store('public/producteurs/pieces');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        }
        if($request->hasFile('copiecarteverso')) {
            try {
                $producteur->copiecarteverso = $request->file('copiecarteverso')->store('public/producteurs/pieces');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        }
        if($request->hasFile('picture')) {
            try {
                $producteur->picture = $request->file('picture')->store('public/producteurs/photos');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        }   
        $producteur->save(); 

        $notify[] = ['success', isset($message) ? $message : 'Le producteur a été crée avec succès.'];
        return back()->withNotify($notify);
    }


    public function storeinfo(Request $request)
    {
        $validationRule = [ 
            'producteur_id' => 'required|max:255',
            'autresCultures'  => 'required|max:255',
            'age18'  => 'required|max:255',
            'persEcole'  => 'required|max:255',
            'scolarisesExtrait'  => 'required|max:255',
            'travailleurs'  => 'required|max:255',
            'travailleurspermanents'  => 'required|max:255',
            'travailleurstemporaires'  => 'required|max:255',
            'personneBlessee'  => 'required|max:255',
            'typeDocuments'  => 'required|max:255',
            'recuAchat'  => 'required|max:255',
            'mobileMoney'  => 'required|max:255',
            'paiementMM'  => 'required|max:255',
            'compteBanque'  => 'required|max:255',
        ];
 

        $request->validate($validationRule);

        $producteur = Producteur::where('id', $request->producteur_id)->first();

        if ($producteur->status == Status::NO) {
            $notify[] = ['error', 'Ce producteur est désactivé'];
            return back()->withNotify($notify)->withInput();
        }
        
        if($request->id) {
            $infoproducteur = Producteur_info::findOrFail($request->id);
            $message = "L'info du producteur a été mise à jour avec succès";
        } else {
            $infoproducteur = new Producteur_info(); 

            $hasInfoProd = Producteur_info::where('producteur_id', $request->producteur_id)->exists();
            
            if($hasInfoProd) {
                $notify[] = ['error', "L'info existe déjà pour ce producteur. Veuillez apporter des mises à jour."];
                return back()->withNotify($notify);
            }
        } 
        
 
        $infoproducteur->producteur_id = $request->producteur_id; 
        $infoproducteur->foretsjachere  = $request->foretsjachere;
        $infoproducteur->superficie  = $request->superficie;
        $infoproducteur->autresCultures     = $request->autresCultures;
        $infoproducteur->age18    = $request->age18;
        $infoproducteur->persEcole = $request->persEcole;
        $infoproducteur->scolarisesExtrait    = $request->scolarisesExtrait;
        $infoproducteur->travailleurs    = $request->travailleurs;
        $infoproducteur->travailleurspermanents    = $request->travailleurspermanents;
        $infoproducteur->travailleurstemporaires = $request->travailleurstemporaires;
        $infoproducteur->personneBlessee    = $request->personneBlessee;
        $infoproducteur->typeDocuments    = $request->typeDocuments;
        $infoproducteur->recuAchat    = $request->recuAchat;
        $infoproducteur->mobileMoney    = $request->mobileMoney;
        $infoproducteur->operateurMM    = $request->operateurMM;
        $infoproducteur->numeroCompteMM    = $request->numeroCompteMM;
        $infoproducteur->paiementMM    = $request->paiementMM;
        $infoproducteur->compteBanque    = $request->compteBanque;  
        
        $infoproducteur->save(); 

        if($infoproducteur !=null ){

            $id = $infoproducteur->id;
            
              if(($request->typeculture !=null)) {

                $verification   = Producteur_infos_typeculture::where('producteur_info_id',$id)->get();
            if($verification->count()){ 
                DB::table('producteur_infos_typecultures')->where('producteur_info_id',$id)->delete();
            }
                $i=0;
                
                foreach($request->typeculture as $data){
                    if($data !=null)
                    {
                        DB::table('producteur_infos_typecultures')->insert(['producteur_info_id' => $id, 'typeculture' => $data, 'superficieculture' => $request->superficieculture[$i]]);
                    } 
                  $i++;
                }

            }

            if(($request->maladiesenfants !=null)) {

                $verification   = Producteur_infos_maladieenfant::where('producteur_info_id',$id)->get();
            if($verification->count()){ 
                DB::table('producteur_infos_maladieenfants')->where('producteur_info_id',$id)->delete();
            }
                $i=0;
                
                foreach($request->maladiesenfants as $data){
                    if($data !=null)
                    {
                        DB::table('producteur_infos_maladieenfants')->insert(['producteur_info_id' => $id, 'maladieenfant' => $data]);
                    } 
                  $i++;
                }

            }

          }

           

        $notify[] = ['success', isset($message) ? $message : "L'info du producteur a été crée avec succès."];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Mise à jour de la producteur";
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        $producteur   = Producteur::findOrFail($id);
        return view('staff.producteur.edit', compact('pageTitle', 'localites', 'producteur'));
    } 

    private function generecodeProdApp($nom,$prenoms,$codeApp)
    {
        $action = 'non'; 

        $data = Producteur::select('codeProdapp')->join('localites as l','producteurs.localite_id','=','l.id')->join('cooperatives as c','l.cooperative_id','=','c.id')->where([
          ['codeProdapp','!=',null],['codeApp',$codeApp]])->orderby('producteurs.id','desc')->first();
       
        if($data !=null){
           
            $code = $data->codeProdapp; 
            if($code !=null)
            {
              $chaine_number = Str::afterLast($code,'-');  
              
            }else{ 
            $chaine_number=0;
            }
          }else{ 
            $chaine_number=0;
        }
 
       $lastCode=$chaine_number+1; 
       $codeP= $codeApp.'-'.gmdate('Y').'-'.$lastCode;

       do{

       $verif = Producteur::select('codeProdapp')->where('codeProdapp',$codeP)->orderby('id','desc')->first(); 
        if($verif ==null){
            $action = 'non';
        }else{
            $action = 'oui';
            $code = $codeP;
            $chaine_number = Str::afterLast($code,'-');  
              $lastCode=$chaine_number+1; 
             $codeP= $codeApp.'-'.gmdate('Y').'-'.$lastCode;
        }

    }while($action !='non');

       return $codeP;
    }

    public function status($id)
    {
        return Producteur::changeStatus($id);
    }

    public function localiteStaff($id)
    {
        $localite         = Localite::findOrFail($id);
        $pageTitle      = $localite->name . " Staff List";
        $localiteProducteurs = Producteur::producteur()->where('localite_id', $id)->orderBy('id', 'DESC')->with('localite')->paginate(getPaginate());
        return view('staff.producteur.index', compact('pageTitle', 'localiteProducteurs'));
    } 
    public function exportExcel()
    { 
        $filename = 'producteurs-' . gmdate('dmYhms') . '.xlsx';
        return Excel::download(new ExportProducteurs, $filename);
    }

    public function  uploadContent(Request $request)
    {
        Excel::import(new ProducteurImport, $request->file('uploaded_file'));
        return back();
    }
    
}
