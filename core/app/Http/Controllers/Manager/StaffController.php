<?php

namespace App\Http\Controllers\Manager;

use App\Exports\ExportStaffs;
use App\Http\Controllers\Controller;
use App\Models\Localite;
use App\Models\Magasin_section;
use App\Models\User;
use App\Models\User_localite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function create()
    {
        $pageTitle = "Ajouter un Staff";
        //recuperation des roles
        $roles = Role::latest()->get();
        //fin recuperation
        $localites  = Localite::where('cooperative_id',auth()->user()->cooperative_id)->active()->orderBy('nom')->get();
        
        return view('manager.staff.create', compact('pageTitle','localites','roles'));
    }

    public function index()
    {
        $pageTitle = "Tous les Staff";
        $manager   = auth()->user();
        $staffs    = User::searchable(['username', 'email', 'mobile'])->where(function ($query) use ($manager) {
            $query->staff()->where('cooperative_id', $manager->cooperative_id);
        })->with('cooperative')->orderBy('id', 'DESC')->paginate(getPaginate());

        return view('manager.staff.index', compact('pageTitle', 'staffs'));
    }
    public function magasinIndex($staffId)
    {
        $pageTitle = "Tous les Magasins";
        $manager   = auth()->user();
        $magasins    = Magasin_section::where('staff_id', $staffId)->with('user')->orderBy('id', 'DESC')->paginate(getPaginate());
         
        return view('manager.staff.magasin', compact('pageTitle', 'magasins','staffId'));
    }

    public function edit($id ,User $user)
    {
        try {
            $id = decrypt($id);
        } catch (Exception $ex) {
            $notify[] = ['error', "Invalid URL."];
            return back()->withNotify($id);
        }

        $pageTitle = "Mise à jour du Staff";
        $manager   = auth()->user();
        $staff     = User::where('id', $id)->where('cooperative_id', $manager->cooperative_id)->firstOrFail();
        $localites  = Localite::where('cooperative_id',auth()->user()->cooperative_id)->active()->orderBy('nom')->get();
        //ajout des roles et le role de l'étulisateur dans la vue manager.staff.edit
        $userRole = $staff->roles->pluck('name')->toArray();

        $roles = Role::latest()->get();
        //fin
        $userLocalite = array();
        $dataLocalite = $staff->userLocalites;
        if($dataLocalite->count()){
            foreach($dataLocalite as $data){
                $userLocalite[]=$data->localite_id;
            }
        }
        return view('manager.staff.edit', compact('pageTitle', 'staff','localites','userLocalite','userRole','roles'));
    }

    public function store(Request $request)
    {
        $manager        = auth()->user();
        $validationRule = [
            'firstname' => 'required|max:40',
            'lastname'  => 'required|max:40',
        ];

        if ($request->id) {
            $validationRule = array_merge($validationRule, [
                'email'    => 'required|email|max:40|unique:users,email,' . $request->id,
                'username' => 'required|max:40|unique:users,username,' . $request->id,
                'mobile'   => 'required|max:40|unique:users,mobile,' . $request->id,
                'password' => 'nullable|confirmed|min:4',
                'role'   => 'required|max:40',
                'type_compte'   => 'required|max:40',
            ]);
        } else {
            $validationRule = array_merge($validationRule, [
                'email'    => 'required|email|max:40|unique:users',
                'username' => 'required|max:40|unique:users',
                'mobile'   => 'required|max:40|unique:users',
                'password' => 'required|confirmed|min:4',
                'role'   => 'required|max:40',
                'type_compte'   => 'required|max:40', 
            ]);
        }

        $request->validate($validationRule);

        $staff = new User();
        

        if($request->id) {
            $staff   = User::where('id', $request->id)->where('cooperative_id', $manager->cooperative_id)->firstOrFail();
            $message = "Staff updated successfully";
        }
        if(($request->type_compte =='web') ||  ($request->type_compte =='mobile-web')) {
            $hasStaff = User::where('cooperative_id', auth()->user()->cooperative_id)->where(function ($query) {
                $query->orwhere('type_compte','web');
                $query->orwhere('type_compte','mobile-web');
                })->count();
            if ($hasStaff>= auth()->user()->cooperative->web) {
                $nombre = auth()->user()->cooperative->web;
                $notify[] = ['error', "Cette coopérative a atteint le nombre de compte Web qui est de : $nombre utilisateurs"];
                return back()->withNotify($notify)->withInput();
            }
        }
        if(($request->type_compte =='mobile') ||  ($request->type_compte =='mobile-web')) {
            $hasStaff = User::where('cooperative_id', auth()->user()->cooperative_id)->where(function ($query) {
                $query->orwhere('type_compte','mobile');
                $query->orwhere('type_compte','mobile-web');
                })->count();
            if ($hasStaff>=auth()->user()->cooperative->mobile) {
                $nombre = auth()->user()->cooperative->mobile;
                $notify[] = ['error', "Cette coopérative a atteint le nombre de compte Web qui est de : $nombre utilisateurs"];
                return back()->withNotify($notify)->withInput();
            }
        }

        $staff->cooperative_id = $manager->cooperative_id;
        $staff->firstname = $request->firstname;
        $staff->lastname  = $request->lastname;
        $staff->username  = $request->username;
        $staff->email     = $request->email;
        $staff->mobile    = $request->mobile;
        $staff->adresse    = $request->adresse;
        $staff->user_type = $request->role; 
        $staff->type_compte = $request->type_compte; 
        $staff->password  = $request->password ? Hash::make($request->password) : $staff->password;
        //$staff->syncRoles($request->get('rolePermission'));
        $staff->save();

        if($staff !=null ){

            $staff->syncRoles($request->role);

            $id = $staff->id;
            
              if(($request->localite !=null)) {

                $verification   = User_localite::where('user_id',$id)->get();
            if($verification->count()){ 
                DB::table('user_localites')->where('user_id',$id)->delete();
            }
                $i=0;
                
                foreach($request->localite as $data){
                    if($data !=null)
                    {
                        DB::table('user_localites')->insert(['user_id'=>$id,'localite_id'=>$data]);
                    } 
                  $i++;
                }

            }
        }else{
            DB::table('model_has_roles')->where('model_id',$request->id)->delete();
            $staff->syncRoles($request->get('role'));
          }

        if (!$request->id) {
            notify($staff, 'STAFF_CREATE', [
                'username' => $staff->username,
                'email'    => $staff->email,
                'password' => $request->password,
            ]);
        }

        $notify[] = ['success', isset($message) ? $message : 'Staff added successfully'];
        return back()->withNotify($notify);
    }
    public function staffLogin($id)
    {
        User::staff()->where('id', $id)->firstOrFail();
        auth()->loginUsingId($id);
        return to_route('staff.dashboard');
    }
    
    public function magasinStore(Request $request)
    {
        $request->validate([
            'nom'  => 'required',
            'staff'  => 'required',
            'phone'  => 'required', 
        ]);

        if ($request->id) {
            $magasin    = Magasin_section::findOrFail($request->id);
            $message = "Magasin a été mise à jour avec succès.";
        } else {
            $magasin = new Magasin_section();
            $magasin->code = $this->generecodemagasin();
        }
        $magasin->nom    = $request->nom ;
        $magasin->staff_id = $request->staff;
        $magasin->phone = $request->phone;
        $magasin->email = $request->email;
        $magasin->adresse   = $request->adresse;
        $magasin->save();
        $notify[] = ['success', isset($message) ? $message  : 'Le magasin a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }
    private function generecodemagasin()
    {

        $data = Magasin_section::select('code')->orderby('id','desc')->first();

        if($data !=''){
            $code = $data->code;
        $chaine_number = Str::afterLast($code,'-');
        if($chaine_number<10){$zero="00000";}
        else if($chaine_number<100){$zero="0000";}
        else if($chaine_number<1000){$zero="000";}
        else if($chaine_number<10000){$zero="00";}
        else if($chaine_number<100000){$zero="0";}
        else{$zero="";}
        }else{
            $zero="00000";
            $chaine_number=0;
        }
        $sub='MAG-';
        $lastCode=$chaine_number+1;
        $codeMagasinsections=$sub.$zero.$lastCode;

        return $codeMagasinsections;
    }
    public function status($id)
    {
        return User::changeStatus($id);
    }
    public function magasinStatus($id)
    {
        return Magasin_section::changeStatus($id);
    }
    public function exportExcel()
    {
        return (new ExportStaffs())->download('staffs.xlsx');
    }
}
