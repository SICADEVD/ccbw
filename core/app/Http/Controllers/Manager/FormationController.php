<?php

namespace App\Http\Controllers\Manager;

use App\Constants\Status;
use App\Exports\ExportFormations;
use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\Localite; 
use App\Models\Producteur; 
use App\Models\SuiviFormation;
use App\Models\SuiviFormationProducteur;
use App\Models\SuiviFormationTheme;
use App\Models\SuiviFormationVisiteur;
use App\Models\ThemesFormation;
use App\Models\TypeFormation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Excel;

class FormationController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des formations";
        $manager   = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $modules = TypeFormation::active()->get();
        $formations = SuiviFormation::dateFilter()->searchable(['lieu_formation'])->latest('id')->joinRelationship('localite.section')->where('sections.cooperative_id',$manager->cooperative_id)->where(function ($q) {
            if(request()->localite != null){
                $q->where('localite_id',request()->localite);
            }
            if(request()->module != null){
                $q->where('type_formation_id',request()->module);
            }
        })->with('localite','campagne','typeFormation')->paginate(getPaginate());
         
        return view('manager.formation.index', compact('pageTitle', 'formations','localites','modules'));
    }
 
    public function create()
    {
        $pageTitle = "Ajouter un formation";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        $typeformations  = TypeFormation::all()->pluck('nom','id');
        $themes  = ThemesFormation::with('typeFormation')->get();
        $staffs  = User::staff()->get();
        return view('manager.formation.create', compact('pageTitle', 'producteurs','localites','typeformations','themes','staffs'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'localite'    => 'required|exists:localites,id',
            'staff' => 'required|exists:users,id',
            'producteur' => 'required|max:255',
            'lieu_formation'  => 'required|max:255',
            'type_formation'  => 'required|max:255',
            'theme'  => 'required|max:255', 
            'date_formation' => 'required|max:255', 
        ];
 

        $request->validate($validationRule);

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivé'];
            return back()->withNotify($notify)->withInput();
        }
        
        if($request->id) {
            $formation = SuiviFormation::findOrFail($request->id); 
            $message = "La formation a été mise à jour avec succès";

        } else {
            $formation = new SuiviFormation();  
        } 
        $campagne = Campagne::active()->first();
        $formation->localite_id  = $request->localite;  
        $formation->campagne_id  = $campagne->id;
        $formation->user_id  = $request->staff;  
        $formation->lieu_formation  = $request->lieu_formation;
        $formation->type_formation_id  = $request->type_formation;
        $formation->date_formation     = $request->date_formation; 
        if($request->hasFile('photo_formation')) {
            try {
                $formation->photo_formation = $request->file('photo_formation')->store('public/formations');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        } 

        $formation->save(); 
        if($formation !=null ){
            $id = $formation->id;
            $datas = $datas2 = $datas3 = $datas4 = [];
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
            if(($request->visiteurs !=null)) { 
                SuiviFormationVisiteur::where('suivi_formation_id',$id)->delete();
                $i=0; 
                foreach($request->visiteurs as $data){
                    if($data !=null)
                    {
                        $datas2[] = [
                        'suivi_formation_id' => $id, 
                        'visiteur' => $data,  
                    ];
                    } 
                  $i++;
                } 
            }
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
            SuiviFormationVisiteur::insert($datas2); 
            SuiviFormationTheme::insert($datas3);
        }
        $notify[] = ['success', isset($message) ? $message : 'Le formation a été crée avec succès.'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Mise à jour de la formation";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites  = Localite::active()->where('cooperative_id',auth()->user()->cooperative_id)->orderBy('nom')->get();
        $typeformations  = TypeFormation::all()->pluck('nom','id');
        $themes  = ThemesFormation::with('typeFormation')->get();
        $staffs  = User::staff()->get();
        $formation   = SuiviFormation::findOrFail($id);
        $suiviProducteur = SuiviFormationProducteur::where('suivi_formation_id',$formation->id)->get();
        $suiviVisiteur = SuiviFormationVisiteur::where('suivi_formation_id',$formation->id)->get();
        $suiviTheme = SuiviFormationTheme::where('suivi_formation_id',$formation->id)->get();
        $dataProducteur = $dataVisiteur=$dataTheme = array();
        if($suiviProducteur->count()){
            foreach($suiviProducteur as $data){
                $dataProducteur[] = $data->producteur_id;
            }
        }
         
        if($suiviTheme->count()){
            foreach($suiviTheme as $data){
                $dataTheme[] = $data->themes_formation_id;
            }
        }
        return view('manager.formation.edit', compact('pageTitle', 'localites', 'formation','producteurs','typeformations','themes','staffs','dataProducteur','suiviVisiteur','dataTheme'));
    } 

    public function status($id)
    {
        return SuiviFormation::changeStatus($id);
    }

    public function exportExcel()
    { 
        $filename = 'formations-' . gmdate('dmYhms') . '.xlsx';
        return Excel::download(new ExportFormations, $filename);
    }
}
