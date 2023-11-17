<?php

namespace App\Http\Controllers\Manager;

use App\Constants\Status;
use App\Exports\ExportFormations;
use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\Localite; 
use App\Models\Producteur; 
use App\Models\FormationStaff;
use App\Models\FormationStaffUser;
use App\Models\FormationThemeStaff;
use App\Models\FormationStaffVisiteur;
use App\Models\ThemeFormationStaff;
use App\Models\ModuleFormationStaff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Excel;

class FormationStaffController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des formations STAFF";
        $manager   = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $modules = ModuleFormationStaff::active()->get();
        $formations = FormationStaff::dateFilter()->searchable(['lieu_formation'])->latest('id')->joinRelationship('cooperative')->where('cooperative_id',$manager->cooperative_id)->where(function ($q) { 
            if(request()->module != null){
                $q->where('module_formation_staff_id',request()->module);
            }
        })->with('cooperative','campagne','ModuleFormationStaff')->paginate(getPaginate());
         
        return view('manager.formation-staff.index', compact('pageTitle', 'formations','localites','modules'));
    }
 
    public function create()
    {
        $pageTitle = "Ajouter un formation";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $ModuleFormationStaffs  = ModuleFormationStaff::all()->pluck('nom','id');
        $themes  = ThemeFormationStaff::with('ModuleFormationStaff')->get();
        $staffs  = User::staff()->get();
        return view('manager.formation-staff.create', compact('pageTitle', 'producteurs','localites','ModuleFormationStaffs','themes','staffs'));
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
            $formation = FormationStaff::findOrFail($request->id); 
            $message = "La formation a été mise à jour avec succès";

        } else {
            $formation = new FormationStaff();  
        } 
        $campagne = Campagne::active()->first();
        $formation->localite_id  = $request->localite;  
        $formation->campagne_id  = $campagne->id;
        $formation->user_id  = $request->staff;  
        $formation->lieu_formation  = $request->lieu_formation;
        $formation->module_formation_staff_id  = $request->type_formation;
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
                FormationStaffUser::where('suivi_formation_id',$id)->delete();
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
                FormationStaffVisiteur::where('suivi_formation_id',$id)->delete();
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
                FormationThemeStaff::where('suivi_formation_id',$id)->delete();
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
            FormationStaffUser::insert($datas);
            FormationStaffVisiteur::insert($datas2); 
            FormationThemeStaff::insert($datas3);
        }
        $notify[] = ['success', isset($message) ? $message : 'Le formation a été crée avec succès.'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Mise à jour de la formation";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id',$manager->cooperative_id],['localites.status',1]])->get();
        $ModuleFormationStaffs  = ModuleFormationStaff::all()->pluck('nom','id');
        $themes  = ThemeFormationStaff::with('ModuleFormationStaff')->get();
        $staffs  = User::staff()->get();
        $formation   = FormationStaff::findOrFail($id);
        $suiviProducteur = FormationStaffUser::where('suivi_formation_id',$formation->id)->get();
        $suiviVisiteur = FormationStaffVisiteur::where('suivi_formation_id',$formation->id)->get();
        $suiviTheme = FormationThemeStaff::where('suivi_formation_id',$formation->id)->get();
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
        return view('manager.formation-staff.edit', compact('pageTitle', 'localites', 'formation','producteurs','ModuleFormationStaffs','themes','staffs','dataProducteur','suiviVisiteur','dataTheme'));
    } 

    public function status($id)
    {
        return FormationStaff::changeStatus($id);
    }

    public function exportExcel()
    { 
        $filename = 'formations-' . gmdate('dmYhms') . '.xlsx';
        return Excel::download(new ExportFormations, $filename);
    }
}
