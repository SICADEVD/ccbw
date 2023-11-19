<?php

namespace App\Http\Controllers\Manager;

use App\Constants\Status;
use App\Exports\ExportFormations;
use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\Localite; 
use App\Models\Producteur; 
use App\Models\FormationStaff;
use App\Models\FormationStaffListe;
use App\Models\FormationStaffTheme;
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
     
        $modules = ModuleFormationStaff::active()->get();
        $formations = FormationStaff::dateFilter()->searchable(['lieu_formation'])->latest('id')->joinRelationship('cooperative')->where('cooperative_id',$manager->cooperative_id)->where(function ($q) { 
            if(request()->module != null){
                $q->where('module_formation_staff_id',request()->module);
            }
        })->with('cooperative','campagne','ModuleFormationStaff')->paginate(getPaginate());
         
        return view('manager.formation-staff.index', compact('pageTitle', 'formations','modules'));
    }
 
    public function create()
    {
        $pageTitle = "Ajouter une formation aux staffs";
        $manager   = auth()->user(); 
       
        $ModuleFormationStaffs  = ModuleFormationStaff::all()->pluck('nom','id');
        $themes  = ThemeFormationStaff::with('ModuleFormationStaff')->get();
        $staffs  = User::get();
        return view('manager.formation-staff.create', compact('pageTitle','ModuleFormationStaffs','themes','staffs'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'staff' => 'required|exists:users,id',
            'user' => 'required|max:255',
            'lieu_formation'  => 'required|max:255',
            'module_formation'  => 'required|max:255',
            'theme'  => 'required', 
            'date_formation' => 'required|max:255', 
        ];
 
        $manager   = auth()->user(); 

        $request->validate($validationRule);
 
        
        if($request->id) {
            $formation = FormationStaff::findOrFail($request->id); 
            $message = "La formation a été mise à jour avec succès";

        } else {
            $formation = new FormationStaff();  
        } 
        $campagne = Campagne::active()->first();
        $formation->cooperative_id  = $manager->cooperative_id;  
        $formation->campagne_id  = $campagne->id;
        $formation->user_id  = $request->staff;  
        $formation->lieu_formation  = $request->lieu_formation;
        $formation->module_formation_staff_id  = $request->module_formation;
        $formation->date_formation     = $request->date_formation; 
        if($request->hasFile('photo_formation')) {
            try {
                $formation->photo_formation = $request->file('photo_formation')->store('public/formation-staffs');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        } 

        $formation->save(); 
        if($formation !=null ){
            $id = $formation->id;
            $datas = $datas2 = $datas3 = $datas4 = [];
            if(($request->user !=null)) { 
                FormationStaffListe::where('formation_staff_id',$id)->delete();
                $i=0; 
                foreach($request->user as $data){
                    if($data !=null)
                    {
                        $datas[] = [
                        'formation_staff_id' => $id, 
                        'user_id' => $data,  
                    ];
                    } 
                  $i++;
                } 
            }
            if(($request->visiteurs !=null)) { 
                FormationStaffVisiteur::where('formation_staff_id',$id)->delete();
                $i=0; 
                foreach($request->visiteurs as $data){
                    if($data !=null)
                    {
                        $datas2[] = [
                        'formation_staff_id' => $id, 
                        'visiteur' => $data,  
                    ];
                    } 
                  $i++;
                } 
            }
            if(($request->theme !=null)) { 
                FormationStaffTheme::where('formation_staff_id',$id)->delete();
                $i=0; 
                foreach($request->theme as $data){
                    if($data !=null)
                    {
                        $datas3[] = [
                        'formation_staff_id' => $id, 
                        'theme_formation_staff_id' => $data,  
                    ];
                    } 
                  $i++;
                } 
            }
            FormationStaffListe::insert($datas);
            FormationStaffVisiteur::insert($datas2); 
            FormationStaffTheme::insert($datas3);
        }
        $notify[] = ['success', isset($message) ? $message : 'Le formation a été crée avec succès.'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Mise à jour de la formation aux staffs";
        $manager   = auth()->user(); 
         
        $ModuleFormationStaffs  = ModuleFormationStaff::all()->pluck('nom','id');
        $themes  = ThemeFormationStaff::with('ModuleFormationStaff')->get();
        $staffs  = User::get();
        $formation   = FormationStaff::findOrFail($id);
        $staffsListe = FormationStaffListe::where('formation_staff_id',$formation->id)->get();
        $visiteurStaff = FormationStaffVisiteur::where('formation_staff_id',$formation->id)->get();
        $themeStaff = FormationStaffTheme::where('formation_staff_id',$formation->id)->get();
        $dataUser = $dataVisiteur=$dataTheme = array();
        if($staffsListe->count()){
            foreach($staffsListe as $data){
                $dataUser[] = $data->user_id;
            }
        } 
         
        if($themeStaff->count()){
            foreach($themeStaff as $data){
                $dataTheme[] = $data->theme_formation_staff_id;
            }
        }
        return view('manager.formation-staff.edit', compact('pageTitle', 'formation','ModuleFormationStaffs','themes','staffs','dataUser','visiteurStaff','dataTheme'));
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
