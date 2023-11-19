<?php

namespace App\Http\Controllers\Manager;

use App\Constants\Status;
use App\Exports\ExportInspections;
use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\CategorieQuestionnaire;
use App\Models\Localite; 
use App\Models\Producteur; 
use App\Models\Inspection;
use App\Models\InspectionQuestionnaire;
use App\Models\Notation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InspectionController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des inspections";
        $manager   = auth()->user();
        $staffs  = User::staff()->get();
        $producteurs  = Producteur::active()->with('localite')->get();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $inspections = Inspection::dateFilter()->searchable([])->latest('id')->joinRelationship('producteur.localite.section')->where('sections.cooperative_id',$manager->cooperative_id)->where(function ($q) {
            if(request()->localite != null){
                $q->where('localite_id',request()->localite);
            }
            if(request()->producteur != null){
                $q->where('producteur_id',request()->producteur);
            }
            if(request()->staff != null){
                $q->where('formateur_id',request()->staff);
            }
        })->with('producteur','user')->paginate(getPaginate());
         
        return view('manager.inspection.index', compact('pageTitle', 'inspections','localites','staffs','producteurs'));
    }
 
    public function create()
    {
        $pageTitle = "Ajouter une inspection";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $staffs  = User::staff()->get();
        $categoriequestionnaire = CategorieQuestionnaire::with('questions')->get();
        $notations = Notation::get();

        return view('manager.inspection.create', compact('pageTitle', 'producteurs','localites','staffs','categoriequestionnaire','notations'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'producteur'    => 'required|exists:producteurs,id',
            'encadreur' => 'required|exists:users,id', 
            'note'  => 'required|max:255',
            'date_evaluation'  => 'required|max:255', 
        ];
 

        $request->validate($validationRule);

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivé'];
            return back()->withNotify($notify)->withInput();
        }
        
        if($request->id) {
            $inspection = Inspection::findOrFail($request->id); 
            $message = "L'inspection a été mise à jour avec succès";

        } else {
            $inspection = new Inspection();  
        } 
        $campagne = Campagne::active()->first();
        $inspection->producteur_id  = $request->producteur;  
        $inspection->campagne_id  = $campagne->id;
        $inspection->formateur_id  = $request->encadreur;
        $inspection->note  = $request->note;
        $inspection->date_evaluation     = $request->date_evaluation; 

        $inspection->save(); 
        if($inspection !=null ){
            $id = $inspection->id;
            $datas = []; 
           
            if(count($request->reponse)) { 
                InspectionQuestionnaire::where('inspection_id',$id)->delete();
                $i=0; 
                foreach($request->reponse as $key=>$value){
                     
                        $datas[] = [
                        'inspection_id' => $id, 
                        'questionnaire_id' => $key, 
                        'notation' => $value, 
                    ];  
                } 
            }
            InspectionQuestionnaire::insert($datas);
        }

        $notify[] = ['success', isset($message) ? $message : 'L\'inspection a été crée avec succès.'];
        return back()->withNotify($notify);
    }
 
    public function edit($id)
    {
        $pageTitle = "Mise à jour de l'inspection";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $staffs  = User::staff()->get();
        $categoriequestionnaire = CategorieQuestionnaire::with('questions')->get();
        $notations = Notation::get();
        $inspection   = Inspection::findOrFail($id); 
        return view('manager.inspection.edit', compact('pageTitle', 'localites', 'inspection','producteurs','staffs','categoriequestionnaire','notations'));
    } 

    public function status($id)
    {
        return Inspection::changeStatus($id);
    }

    public function exportExcel()
    {
        return (new ExportInspections())->download('inspections.xlsx');
    }
    
}
