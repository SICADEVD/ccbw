<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Cooperative;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CooperativeManagerController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestionnaire de coopérative";
        $cooperativeManagers = User::searchable(['username', 'email', 'mobile', 'cooperative:name'])->manager()->latest('id')->with('cooperative')->paginate(getPaginate());
        return view('admin.manager.index', compact('pageTitle', 'cooperativeManagers'));
    }

    public function create()
    {
        $pageTitle = "Ajouter un gestionnaire de coopérative";
        // $cooperatives  = Cooperative::active()->orderBy('name')->get();
        $cooperatives = Cooperative::where('status', Status::YES)->orderBy('name')->get();
        return view('admin.manager.create', compact('pageTitle', 'cooperatives'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'cooperative'    => 'required|exists:cooperatives,id',
            'firstname' => 'required|max:40',
            'lastname'  => 'required|max:40',
        ];

        if ($request->id) {
            $validationRule = array_merge($validationRule, [
                'email'    => 'required|email|max:40|unique:users,email,' . $request->id,
                'username' => 'required|max:40|unique:users,username,' . $request->id,
                'mobile'   => 'required|max:40|unique:users,mobile,' . $request->id,
            ]);
        } else {
            $validationRule = array_merge($validationRule, [
                'email'    => 'required|email|max:40|unique:users',
                'username' => 'required|max:40|unique:users',
                'mobile'   => 'required|max:40|unique:users',
                'password' => 'required|confirmed|min:4',

            ]);
        }

        $request->validate($validationRule);

        $cooperative = Cooperative::where('id', $request->cooperative)->first();

        if ($cooperative->status == Status::NO) {
            $notify[] = ['error', 'Cette coopérative est inactive'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->id) {
            $manager = User::findOrFail($request->id);
            $message = "Le gestionnaire a été mis à jour avec succès";
        } else {
            $manager           = new User();
            $manager->password = Hash::make($request->password);
        }

        // if($manager->cooperative_id != $request->cooperative) {
        //     $hasManager = User::manager()->where('cooperative_id', $request->cooperative)->exists();
        //     if ($hasManager) {
        //         $notify[] = ['error', 'Cette coopérative a déjà un gestionnaire'];
        //         return back()->withNotify($notify)->withInput();
        //     }
        // }


        $manager->cooperative_id = $request->cooperative;
        $manager->firstname = $request->firstname;
        $manager->lastname  = $request->lastname;
        $manager->username  = $request->username;
        $manager->email     = $request->email;
        $manager->mobile    = $request->mobile;
        $manager->password  = $request->password ? Hash::make($request->password) : $manager->password;
        $manager->user_type = "manager";
        $manager->type_compte = "mobile-web";
        $manager->save();

        if (!$request->id) {
            notify($manager, 'MANAGER_CREATE', [
                'username' => $manager->username,
                'email'    => $manager->email,
                'password' => $request->password,
            ]);
        }

        $notify[] = ['success', isset($message) ? $message : 'Le gestionnaire a été ajouté avec succès'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Mise à jour du gestionnaire de coopérative";
        $cooperatives  = Cooperative::active()->orderBy('name')->get();
        $manager   = User::findOrFail($id);
        return view('admin.manager.edit', compact('pageTitle', 'cooperatives', 'manager'));
    }

    public function staffList($cooperativeId)
    {
        $pageTitle = "Liste de Staffs";
        $staffs = User::searchable(['username', 'email', 'mobile', 'cooperative:name'])->staff()->where('cooperative_id', $cooperativeId)->with('cooperative')->paginate(getPaginate());
        return view('admin.manager.staff', compact('pageTitle', 'staffs'));
    }

    public function status($id)
    {
        return User::changeStatus($id);
    }

    public function login($id)
    {
        User::manager()->where('id', $id)->firstOrFail();
        auth()->loginUsingId($id);
        return to_route('manager.dashboard');
    }

    public function staffLogin($id)
    {
        User::staff()->where('id', $id)->firstOrFail();
        auth()->loginUsingId($id);
        return to_route('staff.dashboard');
    }

    public function cooperativeManager($id)
    {
        $cooperative         = Cooperative::findOrFail($id);
        $pageTitle      = $cooperative->name . "Liste des Managers";
        $cooperativeManagers = User::manager()->where('cooperative_id', $id)->orderBy('id', 'DESC')->with('cooperative')->paginate(getPaginate());
        return view('admin.manager.index', compact('pageTitle', 'cooperativeManagers'));
    }
}
