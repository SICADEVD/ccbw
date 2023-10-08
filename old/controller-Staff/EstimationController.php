<?php

namespace App\Http\Controllers\Staff;

use App\Constants\Status;
use App\Exports\ExportEstimations;
use App\Http\Controllers\Controller;
use App\Imports\EstimationImport;
use App\Models\Localite; 
use App\Models\Producteur; 
use App\Models\Estimation; 
use App\Models\Parcelle; 
use App\Models\Campagne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Excel;

class EstimationController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des estimations";
        $manager   = auth()->user();
        $localites = Localite::active()->where('cooperative_id',$manager->cooperative_id)->get();
        $estimations = Estimation::dateFilter()->searchable(["EA1","EA2","EA3","EB1","EB2","EB3","EC1","EC2","EC3","T1","T2","T3","V1","V2","V3","VM1","VM2","VM3","Q","RF","EsP","date_estimation","productionAnnuelle"])->latest('id')->joinRelationship('parcelle.producteur.localite')->where('cooperative_id',$manager->cooperative_id)->where(function ($q) {
            if(request()->localite != null){
                $q->where('localite_id',request()->localite);
            }
            if(request()->status != null){
                $q->where('estimations.status',request()->status);
            }
        })->with('parcelle')->paginate(getPaginate());
         
        return view('staff.estimation.index', compact('pageTitle', 'estimations','localites'));
    }
 
    public function create()
    {
        $pageTitle = "Ajouter une estimation";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        $campagnes = Campagne::active()->pluck('nom','id');
        $parcelles  = Parcelle::with('producteur')->get();
        return view('staff.estimation.create', compact('pageTitle', 'producteurs','localites','campagnes','parcelles'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'parcelle'    => 'required|exists:parcelles,id',
            'campagne' => 'required|max:255',
            'Q'  => 'required|max:255',
            'RF'  => 'required|max:255',
            'EsP'  => 'required|max:255', 
            'date_estimation'  => 'required|max:255', 
        ];
 

        $request->validate($validationRule);

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivée'];
            return back()->withNotify($notify)->withInput();
        }
        
        if($request->id) {
            $estimation = Estimation::findOrFail($request->id);
            $message = "L'estimation a été mise à jour avec succès";
        } else {
            $estimation = new Estimation();  
        } 
        
        $estimation->parcelle_id  = $request->parcelle;  
        $estimation->campagne_id  = $request->campagne;
        $estimation->EA1  = $request->EA1;
        $estimation->EA2  = $request->EA2;
        $estimation->EA3  = $request->EA3;
        $estimation->EB1  = $request->EB1;
        $estimation->EB2  = $request->EB2;
        $estimation->EB3  = $request->EB3;
        $estimation->EC1  = $request->EC1;
        $estimation->EC2  = $request->EC2;
        $estimation->EC3  = $request->EC3;
        $estimation->T1 = $request->T1; 
        $estimation->T2 = $request->T2;
        $estimation->T3 = $request->T3;
        $estimation->V1 = $request->V1;  
        $estimation->V2 = $request->V2; 
        $estimation->V3 = $request->V3; 
        $estimation->VM1    = $request->VM1;
        $estimation->VM2    = $request->VM2;
        $estimation->VM3    = $request->VM3;
        $estimation->Q    = $request->Q;
        $estimation->RF    = $request->RF;
        $estimation->EsP    = $request->EsP;
        $estimation->date_estimation    = $request->date_estimation; 

        $estimation->save(); 

        $notify[] = ['success', isset($message) ? $message : "L'estimation a été crée avec succès."];
        return back()->withNotify($notify);
    }

     

    public function edit($id)
    {
        $pageTitle = "Mise à jour de la estimation";
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        $producteurs  = Producteur::with('localite')->get();
        $estimation   = Estimation::findOrFail($id);
        return view('staff.estimation.edit', compact('pageTitle', 'localites', 'estimation','producteurs'));
    } 

    public function status($id)
    {
        return Estimation::changeStatus($id);
    }

    public function exportExcel()
    {
        return (new ExportEstimations())->download('estimations.xlsx');
    }
    public function  uploadContent(Request $request)
    {
        Excel::import(new EstimationImport, $request->file('uploaded_file'));
        return back();
    }
}
