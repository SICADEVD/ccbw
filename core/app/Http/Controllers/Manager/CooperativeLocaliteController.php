<?php

namespace App\Http\Controllers\Manager;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Imports\LocaliteImport;
use App\Models\Cooperative;
use App\Models\Localite;
use App\Models\Localite_ecoleprimaire;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Excel;

class CooperativeLocaliteController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des localités"; 
        $manager   = auth()->user();
        $cooperativeLocalites = Localite::searchable(['localites.nom', 'localites.codeLocal', 'localites.type_localites', 'localites.sousprefecture', 'sections.libelle'])->latest('id')->joinRelationship('section')->where('cooperative_id',$manager->cooperative_id)->with('cooperative')->paginate(getPaginate());
        $cooperatives = Cooperative::active()->where('id',$manager->cooperative_id)->get();
        $sections = Section::all();
        return view('manager.localite.index', compact('pageTitle', 'cooperativeLocalites','cooperatives','sections'));
    }

    public function create()
    {
        $pageTitle = "Ajouter une localité";
        $manager   = auth()->user();
        $sections = Section::where('cooperative_id',$manager->cooperative_id)->get();
        return view('manager.localite.create', compact('pageTitle', 'sections','manager'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'section_id'    => 'required|exists:sections,id',
            'nom' => 'required|max:255',
            'type_localites'  => 'required|max:255',
            'sousprefecture'  => 'required|max:255',
            'centresante'  => 'required|max:255',
            'ecole'  => 'required|max:255',
            'sources_eaux'  => 'required|max:255',
            'electricite'  => 'required|max:255',
            'marche'  => 'required|max:255',
            'deversementDechets'  => 'required|max:255',
        ];

        $manager   = auth()->user();
 

        $request->validate($validationRule);

        $cooperative = $manager->cooperative;

        if ($cooperative->status == Status::NO) {
            $notify[] = ['error', 'Cette coopérative est désactivé'];
            return back()->withNotify($notify)->withInput();
        }
        
        if($request->id) {
            $localite = Localite::findOrFail($request->id);
            $message = "La localité a été mise à jour avec succès";
        } else {
            $localite           = new Localite(); 
            $localite->nom = $this->verifylocalite($request->nom);
        } 
        $localite->section_id = $request->section_id;
        $localite->userid = $manager->id;
        $localite->nom = $request->nom;
        $localite->type_localites  = $request->type_localites;
        $localite->sousprefecture  = $request->sousprefecture;
        $localite->population     = $request->population;
        $localite->centresante    = $request->centresante;
        $localite->kmCentresante    = $request->kmCentresante;
        $localite->typecentre    = $request->typecentre;
        $localite->nomCentresante    = $request->nomCentresante;
        $localite->ecole    = $request->ecole;
        $localite->kmEcoleproche    = $request->kmEcoleproche;
        $localite->nomEcoleproche    = $request->nomEcoleproche;
        $localite->nombrecole    = $request->nombrecole;
        $localite->sources_eaux    = $request->sources_eaux;
        $localite->etatpompehydrau    = $request->etatpompehydrau;
        $localite->electricite    = $request->electricite;
        $localite->marche    = $request->marche;
        $localite->jourmarche    = $request->jourmarche;
        $localite->kmmarcheproche    = $request->kmmarcheproche;
        $localite->deversementDechets    = $request->deversementDechets;
        $localite->comiteMainOeuvre    = $request->comiteMainOeuvre;
        $localite->associationFemmes    = $request->associationFemmes;
        $localite->associationJeunes    = $request->associationJeunes;
        $localite->localongitude    = $request->localongitude;
        $localite->localatitude    = $request->localatitude; 
         
        $localite->codeLocal    = isset($request->codeLocal) ? $request->codeLocal : $this->generelocalitecode($request->nom); 
        $localite->save();

        if($localite !=null ){

            $id = $localite->id;
            
              if(($request->nomecolesprimaires !=null) && $request->nombrecole !=null) {

                $verification   = Localite_ecoleprimaire::where('localite_id',$id)->get();
            if($verification->count()){ 
                DB::table('localite_ecoleprimaires')->where('localite_id',$id)->delete();
            }
                $i=0;
                
                foreach($request->nomecolesprimaires as $data){
                    if($data !=null)
                    {
                        DB::table('localite_ecoleprimaires')->insert(['localite_id'=>$id,'nomecole'=>$data]);
                    } 
                  $i++;
                }

            }
          }

        $notify[] = ['success', isset($message) ? $message : 'La localité a été crée avec succès.'];
        return back()->withNotify($notify);
    }

    private function verifylocalite($nom){
        $action = 'non';
        do{
        $data = Localite::select('nom')->where('nom',$nom)->orderby('id','desc')->first();
        if($data !=''){

            $nomLocal = $data->nom; 
            $nom = Str::beforeLast($nomLocal,' ');
        $chaine_number = Str::afterLast($nomLocal,' '); 
       
        if(is_numeric($chaine_number) && ($chaine_number<10)){$zero="00";}
        else if(is_numeric($chaine_number) && ($chaine_number<100)){$zero="0";}
        else{$zero="00";
            $chaine_number=0;} 
           
        $sub=$nom.' ';
        $lastCode=$chaine_number+1;
        $nomLocal=$sub.$zero.$lastCode; 

        }else{
           
            $nomLocal=$nom;
        }
        $verif = Localite::select('nom')->where('nom',$nomLocal)->orderby('id','desc')->first(); 
        if($verif ==null){
            $action = 'non';
        }else{
            $action = 'oui';
            $nom = $verif->nom;
        }
        
        }while($action !='non');

    return $nomLocal;
    }
    
    private function generelocalitecode($name)
    {
        $action = 'non';
        do{

        $data = Localite::select('codeLocal')->where('nom',$name)->orderby('id','desc')->first();

        if($data !=''){

            $code = $data->codeLocal;

        $chaine_number = Str::afterLast($code,'-');

        if($chaine_number<10){$zero="00";}
        else if($chaine_number<100){$zero="0";}
        else{$zero="";}
        }else{
            $zero="00";
            $chaine_number=0;
        }

        $abrege=Str::upper(Str::substr($name,0,3));
        $sub=$abrege.'-';
        $lastCode=$chaine_number+1;
        $codeP=$sub.$zero.$lastCode;

        $verif = Localite::select('nom')->where('codeLocal',$codeP)->orderby('id','desc')->first();
        if($verif ==null){
            $action = 'non';
        }else{
            $action = 'oui';
            $name = $verif->nom;
        }

        }while($action !='non');

        return $codeP;
    }

    public function edit($id)
    {
        $pageTitle = "Mise à jour de la localité";
        $manager   = auth()->user();
        $localite   = Localite::findOrFail($id);
        $sections = Section::where('cooperative_id',$manager->cooperative_id)->get();
        return view('manager.localite.edit', compact('pageTitle', 'sections', 'localite','manager'));
    } 

    public function status($id)
    {
        return Localite::changeStatus($id);
    }

    public function cooperativeManager($id)
    {
        $cooperative         = Cooperative::findOrFail($id);
        $pageTitle      = $cooperative->name . " Manager List";
        $cooperativeLocalites = Localite::localite()->where('cooperative_id', $id)->orderBy('id', 'DESC')->with('cooperative')->paginate(getPaginate());
        return view('manager.localite.index', compact('pageTitle', 'cooperativeLocalites'));
    }
    public function  uploadContent(Request $request)
    {
        Excel::import(new LocaliteImport, $request->file('uploaded_file'));
        return back();
    }
}
