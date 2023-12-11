<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\Localite;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use App\Models\SuiviFormation;
use App\Models\ThemeSousTheme;
use App\Models\Suivi_formation;
use App\Models\TypeFormationTheme;
use Illuminate\Support\Facades\DB;
use App\Models\SuiviFormationTheme;
use Illuminate\Support\Facades\File;
use App\Models\SuiviFormationVisiteur;
use App\Models\SuiviFormationProducteur;

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
            'duree_formation' => 'required|date_format:H:i',
            'theme'  => 'required|max:255',
        ];

        $request->validate($validationRule);
        if ($request->id) {
            $formation = SuiviFormation::findOrFail($request->id);
        } else {
            $formation = new SuiviFormation();
        }

        $campagne = Campagne::active()->first();
        $formation->localite_id  = $request->localite;
        $formation->campagne_id  = $campagne->id;
        $formation->user_id  = $request->staff;
        $formation->lieu_formation  = $request->lieu_formation;
        $formation->duree_formation = $request->duree_formation;
        $formation->observation_formation = $request->observation_formation;
        $formation->formation_type = $request->formation_type;
        $formation->date_debut_formation = $request->multiStartDate;
        $formation->date_fin_formation = $request->multiEndDate;
        $formation->userid = $request->userid;

        $photo_fileNameExtension = Str::afterLast($request->photo_filename, '.');
        $rapport_fileNameExtension = Str::afterLast($request->rapport_filename,'.');


        if ($request->photo_formation) {
            $image = $request->photo_formations;
            $image = Str::after($image, 'base64,');
            $image = str_replace(' ', '+', $image);
            $imageName = (string) Str::uuid() . '.' . $photo_fileNameExtension;
            File::put(storage_path() . "/app/public/formations/" . $imageName, base64_decode($image));
            $photo_formations = "public/formations/$imageName";
            $formation->photo_formation = $photo_formations;
        }

        if($request->rapport_formation){
            $rapport_formation = $request->rapport_formation;
            $rapport_formation = Str::after($rapport_formation, 'base64,');
            $rapport_formation = str_replace(' ', '+', $rapport_formation);
            $rapportName = (string) Str::uuid() . '.' . $rapport_fileNameExtension;
            File::put(storage_path() . "/app/public/formations/" . $rapportName, base64_decode($rapport_formation));
            $rapport_formation = "public/formations/$rapportName";

            $formation->rapport_formation = $rapport_formation;
        }

       
        $formation->save();


        if ($formation != null) {
            $id = $formation->id;
            $datas = $datas2 = [];

            if (($request->producteur != null)) {
                SuiviFormationProducteur::where('suivi_formation_id', $id)->delete();
                $i = 0;
                foreach ($request->producteur as $data) {
                    if ($data != null) {
                        $datas[] = [
                            'suivi_formation_id' => $id,
                            'producteur_id' => $data,
                        ];
                    }
                    $i++;
                }
                SuiviFormationProducteur::insert($datas);
            }

            $selectedThemes = $request->theme;
            if ($selectedThemes != null) {
                TypeFormationTheme::where('suivi_formation_id', $id)->delete();

                foreach ($selectedThemes as $themeId) {
                    list($typeFormationId, $themeItemId) = explode('-', $themeId);
                    $datas3 = [
                        'suivi_formation_id' => $id,
                        'type_formation_id' => $typeFormationId,
                        'theme_formation_id' => $themeItemId,
                    ];
                    TypeFormationTheme::insert($datas3);
                }
    
            }
            
            $selectedSousThemes = $request->sous_theme;
            if ($selectedSousThemes != null) {
                ThemeSousTheme::where('suivi_formation_id', $id)->delete();
                foreach ($selectedSousThemes as $sthemeId) {
                    list($themeFormationId, $sousthemeItemId) = explode('-', $sthemeId);
                    $datas2 = [
                        'suivi_formation_id' => $id,
                        'theme_id' => $themeFormationId,
                        'sous_theme_id' => $sousthemeItemId,
                    ];
                    ThemeSousTheme::insert($datas2);
                }
            }
        }


        return response()->json($formation, 201);
    }
    
    public function getvisiteurs(Request $request)
    {
        $suivi_formation_id = $request->suivi_formation_id;
        $visiteurs = SuiviFormationVisiteur::where('suivi_formation_id', $suivi_formation_id)->get();
        return response()->json($visiteurs);
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
