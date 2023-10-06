<?php

namespace App\Http\Controllers\Manager;

use App\Constants\Status;
use App\Exports\ExportAgroapprovisionnements;
use App\Http\Controllers\Controller;
use App\Imports\AgroapprovisionnementImport;
use App\Models\Localite; 
use App\Models\Section; 
use App\Models\Producteur; 
use App\Models\Agroapprovisionnement;
use App\Models\AgroapprovisionnementEspece;
use App\Models\Agroespecesarbre;
use App\Models\Agroevaluation;
use App\Models\Campagne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Excel;

class AgroapprovisionnementController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des approvisionnements";
        $manager   = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $approvisionnements = Agroapprovisionnement::dateFilter()->searchable([])->latest('id')->where('cooperative_id',$manager->cooperative_id)->where(function ($q) {
            if(request()->localite != null){
                $q->where('localite_id',request()->localite);
            }
        })->paginate(getPaginate());
         
        return view('manager.approvisionnement.index', compact('pageTitle', 'approvisionnements','localites'));
    }
 
    public function create()
    {
        $pageTitle = "Ajouter un approvisionnement";
        $manager   = auth()->user();
        $especesarbres  = Agroespecesarbre::get(); 
        return view('manager.approvisionnement.create', compact('pageTitle', 'especesarbres'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'especesarbre'            => 'required|array',
            'quantite'            => 'required|array',  
        ];
 

        $request->validate($validationRule);
 
        if($request->id) {
            $approvisionnement = Agroapprovisionnement::findOrFail($request->id); 
            $message = "La approvisionnement a été mise à jour avec succès";

        } else {
            $approvisionnement = new Agroapprovisionnement();  
        } 
        $manager   = auth()->user();
        $campagne = Campagne::active()->first(); 

        if(!$request->id) {
            $hasCooperative = Agroapprovisionnement::where([['cooperative_id', $manager->cooperative_id],['campagne_id', $campagne->id]])->exists();
            if ($hasCooperative) {
                $notify[] = ['error', 'Cette coopérative a déjà été approvisionnée pour cette campagne.'];
                return back()->withNotify($notify)->withInput();
            }
        } 

        $approvisionnement->campagne_id = $campagne->id;
        $approvisionnement->cooperative_id = $manager->cooperative_id;
         
        if($request->hasFile('bon_livraison')) {
            try {
                $approvisionnement->bon_livraison = $request->file('bon_livraison')->store('public/approvisionnements');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        } 
        $approvisionnement->save();

        $datas = [];
        $k=0;
        $i=0;
        
        if($approvisionnement !=null ){
            $id = $approvisionnement->id;
        if($request->especesarbre !=null) {  
            AgroapprovisionnementEspece::where('agroapprovisionnement_id',$id)->delete();
            $quantite = $request->quantite;
            foreach($request->especesarbre as $key => $data){
                 
                $total = $quantite[$key];  
                     if($total !=null)
                    {   
                    $datas[] = [ 
                        'agroapprovisionnement_id'=>$id,
                        'agroespecesarbre_id' => $data,  
                        'total' => $total,  
                    ]; 
                    $i++;
                    }else{
                        $k++;
                    }
                      
            } 
            AgroapprovisionnementEspece::insert($datas); 
        }
    }
    $notify[] = ['success', isset($message) ? $message : "$i nouveau(x) types d'arbres à ombrage ont été ajoutés."];
         
        return back()->withNotify($notify);
    } 

    public function edit($id)
    {
        $pageTitle = "Mise à jour de la approvisionnement";
         
        $especesarbres  = Agroespecesarbre::get(); 
        $approvisionnement   = Agroapprovisionnement::findOrFail($id);
        return view('manager.approvisionnement.edit', compact('pageTitle', 'especesarbres', 'approvisionnement'));
    } 

    public function status($id)
    {
        return Agroapprovisionnement::changeStatus($id);
    }

    public function exportExcel()
    {
        return (new ExportAgroapprovisionnements())->download('approvisionnements.xlsx');
    }

    public function  uploadContent(Request $request)
    {
        Excel::import(new AgroapprovisionnementImport, $request->file('uploaded_file'));
        return back();
    }
}
