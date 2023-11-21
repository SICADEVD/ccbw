<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\Localite;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use App\Models\SuiviFormation;
use App\Models\Suivi_formation;
use Illuminate\Support\Facades\DB;
use App\Models\SuiviFormationTheme;
use Illuminate\Support\Facades\File;
use App\Models\SuiviFormationVisiteur;
use App\Models\SuiviFormationProducteur;
use App\Constants\Status;

class ApisuiviformationController extends Controller
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
		 

        if(!file_exists(storage_path(). "/app/public/formations"))
        { 
            File::makeDirectory(storage_path(). "/app/public/formations", 0777, true);
        }
        
        $validationRule = [
            'localite'    => 'required|exists:localites,id',
            'staff' => 'required|exists:users,id',
            'producteur' => 'required|max:255',
            'lieu_formation'  => 'required|max:255',
            'type_formation'  => 'required|max:255',
            'formation_type'  => 'required|max:255',
            'observation_formation'  => 'required|max:255',
            'duree_formation' => 'required|date_format:H:i',
            'theme'  => 'required|max:255', 
            'date_formation' => 'required|max:255', 
        ];
 

        $request->validate($validationRule);

         
        $formation = new SuiviFormation();

        $campagne = Campagne::active()->first();
        $formation->localite_id  = $request->localite;
        $formation->campagne_id  = $campagne->id;
        $formation->user_id  = $request->staff;
        $formation->lieu_formation  = $request->lieu_formation;
        $formation->type_formation_id  = $request->type_formation;
        $formation->duree_formation = $request->duree_formation;
        $formation->observation_formation = $request->observation_formation;
        $formation->formation_type = $request->formation_type;
        $formation->date_formation     = $request->date_formation;
        $formation->userid = $request->userid;
       
       if($request->photo_formations){  
        $image = $request->photo_formations;  
        $image = Str::after($image,'base64,');
        $image = str_replace(' ', '+', $image);
        $imageName = (string) Str::uuid().'.'.'jpg';
         File::put(storage_path(). "/app/public/formations/" . $imageName, base64_decode($image)); 
        $photo_formations = "public/formations/$imageName";
        $input['photo_formation'] = $photo_formations; 
      }
      $formation->save(); 
      

        if($formation !=null ){
            $id = $formation->id;
            $datas = $datas2 = $datas3 = [];
            if(($request->producteur !=null)) { 
                SuiviFormationProducteur::where('suivi_formation_id',$id)->delete();
                $i=0; 
                foreach($request->producteur as $data){
                    if($data !=null)
                    {
                        $datas[] = [
                        'suivi_formation_id' => $id, 
                        'producteur_id' => $data,  
                    ];
                    } 
                  $i++;
                } 
            }
            // if(($request->visiteurs !=null)) { 
            //     SuiviFormationVisiteur::where('suivi_formation_id',$id)->delete();
            //     $i=0; 
            //     foreach($request->visiteurs as $data){
            //         if($data !=null)
            //         {
            //             $datas2[] = [
            //             'suivi_formation_id' => $id, 
            //             'visiteur' => $data,  
            //         ];
            //         } 
            //       $i++;
            //     } 
            // }
            if(($request->theme !=null)) { 
                SuiviFormationTheme::where('suivi_formation_id',$id)->delete();
                $i=0; 
                foreach($request->theme as $data){
                    if($data !=null)
                    {
                        $datas3[] = [
                        'suivi_formation_id' => $id, 
                        'themes_formation_id' => $data,  
                    ];
                    } 
                  $i++;
                } 
            }
            SuiviFormationProducteur::insert($datas);
            // SuiviFormationVisiteur::insert($datas2); 
            SuiviFormationTheme::insert($datas3);
        }

        return response()->json($formation, 201);
    }

    public function storeVisiteur(Request $request){
        $validationRule = [
            'nom'  => 'required|max:255',
            'prenom'  => 'required|max:255',
            'sexe'  => 'required|max:255',
            'telephone'  => 'required|regex:/^\d{10}$/|unique:suivi_formation_visiteurs,telephone',
        ];
        $request->validate($validationRule);
        $localite = Localite::where('id', $request->localite)->first();
        if ($localite->status == Status::NO) {
            return response()->json(['message' => 'Cette localité est désactivée'], 401);
        }
        $visiteur = new SuiviFormationVisiteur();
        $visiteur->producteur_id  = $request->producteur;
        $visiteur->nom  = $request->nom;
        $visiteur->prenom  = $request->prenom;
        $visiteur->sexe  = $request->sexe;
        $visiteur->telephone  = $request->telephone;
        $visiteur->lien = $request->lien;
        $visiteur->autre_lien = $request->autre_lien;
        $visiteur->representer = $request->representer;
        $visiteur->suivi_formation_id = $request->suivi_formation_id;
        $visiteur->save();
        return response()->json($visiteur, 201);
    }

    public function getTypethemeformation(){
        $typeformations = DB::table('type_formations')->select('nom','id')->get();
        $donnees = DB::table('themes_formations')->get();
        $type_formations_theme = array();
        foreach($typeformations as $res)
        {
 
            foreach($donnees as $data){
                if($data->type_formation_id==$res->id){
                    $gestlist[] = array('id'=>$data->id, 'libelle'=>$data->nom);
                    
                }
            }
            $type_formations_theme[] = array(
                'titretype'=>$res->nom,
                'idtype'=>$res->id,
                 "theme"=>$gestlist); 
             
             $gestlist =array(); 
        }
        return response()->json($type_formations_theme , 201);
    }

    public function getTypeformation(){

        $typeformations = DB::table('type_formations')->get(); 
         
        return response()->json($typeformations , 201);
    }
    public function getThemes(){
        
        $themes = DB::table('themes_formations')->get(); 
         
        return response()->json($themes , 201);
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
