<?php

namespace App\Http\Controllers\Manager;

use App\Constants\Status;
use App\Exports\ExportAgroevaluations;
use App\Exports\ExportParcelles;
use App\Http\Controllers\Controller;
use App\Imports\ParcelleImport;
use App\Models\Agroevaluation;
use App\Models\Campagne;
use App\Models\Localite; 
use App\Models\Producteur; 
use App\Models\Parcelle; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Excel;

class AgroevaluationController extends Controller
{

    public function index()
    {
        $pageTitle      = "Evaluation des besoins en arbres";
        $manager   = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $agroevaluations = Agroevaluation::dateFilter()->searchable([])->latest('id')->joinRelationship('parcelle.producteur.localite.section')->where('sections.cooperative_id',$manager->cooperative_id)->where(function ($q) {
            if(request()->localite != null){
                $q->where('localite_id',request()->localite);
            }
            if(request()->status != null){
                $q->where('agroevaluations.status',request()->status);
            }
        })->with('parcelle')->paginate(getPaginate());
         
        return view('manager.agroevaluation.index', compact('pageTitle', 'agroevaluations','localites'));
    }
 
    public function create()
    {
        $pageTitle = "Ajouter une estimation";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->orderBy('nom')->get();
        $campagnes = Campagne::active()->pluck('nom','id');
        $parcelles  = Parcelle::with('producteur')->get();
       
        return view('manager.agroevaluation.create', compact('pageTitle', 'producteurs','localites','campagnes','parcelles'));
    }

    public function store(Request $request)
    {
        $validationRule = [ 
            'localite'    => 'required|exists:localites,id',
        ];
 

        $request->validate($validationRule);

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivée'];
            return back()->withNotify($notify)->withInput();
        }
        $k=0;
        $i=0;
        $datas = [];  
        if($request->localite){

            if($request->producteur){

                if($request->parcelle !=null) {  
            
                    foreach($request->parcelle as $data){
                        $parc = Parcelle::findOrFail($data);
                        $superficie = $parc->superficie;
                        $quantite = 70 * $superficie;
                        $verification = Agroevaluation::where('parcelle_id', $data)->count();
                        if(!$verification){
                            $datas[] = [
                                'parcelle_id' => $data,  
                                'quantite' => $quantite, 
                                'created_at' =>now()
                            ];  
                            $i++;
                        }else{
                            $k++;
                        }
                           
                    } 
                }else{
                    $parcelle = Parcelle::where('producteur_id', $request->producteur)->get();
                    if($parcelle->count()){ 
                        foreach($parcelle as $data){ 
                            $superficie = $data->superficie;
                            $quantite = 70 * $superficie;
                        $verification = Agroevaluation::where('parcelle_id', $data->id)->count();
                        if(!$verification){
                            $datas[] = [
                                'parcelle_id' => $data->id,  
                                'quantite' => $quantite, 
                                'created_at' =>now()
                            ];
                            $i++;
                        }else{
                            $k++;
                        }
                               
                        }
                    }
                }

            }else{

                $localites = Producteur::where('localite_id', $request->localite)->get();
                if($localites->count()){
                    foreach($localites as $loc){

                        $parcelle = Parcelle::where('producteur_id', $loc->id)->get();
                    if($parcelle->count()){ 
                        foreach($parcelle as $data){ 
                            $superficie = $data->superficie;
                            $quantite = 70 * $superficie;
                        $verification = Agroevaluation::where('parcelle_id', $data->id)->count();
                        if(!$verification){
                            $datas[] = [
                                'parcelle_id' => $data->id,  
                                'quantite' => $quantite, 
                                'created_at' =>now()
                            ];
                            $i++;
                        }else{
                            $k++;
                        }
                               
                        }
                    }

                    }
                }
            }

        }
        
        if(count($datas)){
            Agroevaluation::insert($datas); 
            $notify[] = ['success', "$i parcelles ont été évaluées en besoins d'arbres à ombrage. $k parcelles ont déjà été évaluées."];
        }else{
            $notify[] = ['error', "Aucune parcelle a évaluée"];
        }
         
        return back()->withNotify($notify);
    }

     

    public function edit($id)
    {
        $pageTitle = "Mise à jour de la estimation";
        $manager = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->orderBy('nom')->get();
        $producteurs  = Producteur::with('localite')->get();
        $estimation   = Agroevaluation::findOrFail($id);
        return view('manager.agroevaluation.edit', compact('pageTitle', 'localites', 'estimation','producteurs'));
    } 

    public function status($id)
    {
        return Agroevaluation::changeStatus($id);
    }

    public function destroy($id)
    {
        Agroevaluation::find(decrypt($id))->delete(); 

        $notify[] = ['success', "L'évaluation de cette parcelle a été supprimer avec succès"];
        return back()->withNotify($notify);
    }

    public function exportExcel()
    {
        return (new ExportAgroevaluations())->download('agroevaluations.xlsx');
    }
     
}
