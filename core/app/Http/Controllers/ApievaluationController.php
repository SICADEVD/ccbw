<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\Localite;
use App\Constants\Status;
use App\Models\Evaluation;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\DB;
use App\Models\InspectionQuestionnaire;

class ApievaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        $inspection->certificat  = json_encode($request->certificat);
        $inspection->note  = $request->note;
        $inspection->total_question  = $request->total_question;
        $inspection->total_question_conforme  = $request->total_question_conforme;
        $inspection->total_question_non_conforme  = $request->total_question_non_conforme;
        $inspection->total_question_non_applicable  = $request->total_question_non_applicable;
        $inspection->date_evaluation     = $request->date_evaluation; 
        $inspection->production = $request->production;
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
        return response()->json($inspection, 201);
    }

    public function getQuestionnaire(){
        $categoriequestionnaire = DB::table('categorie_questionnaires')->get();
        $donnees = DB::table('questionnaires')->get();
        $questionnaires = array();
        $gestlist =array();
        foreach($categoriequestionnaire as $categquest)
        {
 
            foreach($donnees as $data){
                if($data->categorie_questionnaire_id==$categquest->id){
                    $gestlist[] = array('id'=>$data->id, 'libelle'=>$data->nom,'certificat'=>$data->certificat);
                    
                }
            }
            $questionnaires[] = array('titre'=>$categquest->titre, "questionnaires"=>$gestlist); 
             
             $gestlist =array(); 
        }
        
            
        return response()->json($questionnaires , 201);
    }
    public function getNotation(){
        $donnees = DB::table('notations')->get();
        return response()->json($donnees , 201);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	
        //
    }
}
