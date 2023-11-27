<?php

namespace App\Http\Controllers\manager;

use App\Models\Unit;
use App\Models\Instance;
use App\Constants\Status;
use App\Models\Campagne; 
use App\Models\ArretEcole;
use App\Models\Department;
use App\Models\Entreprise;
use App\Http\Helpers\Reply;
use App\Models\Cooperative;
use App\Models\CourierInfo;
use App\Models\Designation;
use App\Models\TypeArchive;
use Illuminate\Http\Request;
use App\Models\Questionnaire;
use App\Models\TravauxLegers;
use App\Models\TypeFormation;
use App\Models\CourierPayment;
use App\Models\CourierProduct;
use App\Models\FormateurStaff;
use App\Models\ThemesFormation;
use App\Models\Agroespecesarbre;
use App\Models\TravauxDangereux;
use Illuminate\Support\Facades\DB;
use App\Models\ThemeFormationStaff;
use App\Http\Controllers\Controller;
use App\Models\ModuleFormationStaff;
use App\Models\DocumentAdministratif;
use App\Models\CategorieQuestionnaire;

class SettingController extends Controller
{
 

    public function campagneIndex()
    {
        $pageTitle = "Manage Campagne"; 
        $activeSettingMenu = 'campagne_settings';
        $campagnes     = Campagne::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.campagne', compact('pageTitle', 'campagnes','activeSettingMenu'));
    }

    public function campagneStore(Request $request)
    {
        $request->validate([
            'produit'  => 'required',
            'nom'  => 'required',
            'periode_debut'  => 'required',
            'periode_fin'  => 'required', 
            'prix_achat' => 'required|gt:0|numeric',
            'prime' => 'required|gt:0|numeric',
        ]);

        if ($request->id) {
            $campagne    = Campagne::findOrFail($request->id);
            $message = "Campagne a été mise à jour avec succès.";
        } else {
            $campagne = new Campagne();
        }
        $campagne->produit    = $request->produit ;
        $campagne->nom = $request->nom;
        $campagne->periode_debut = $request->periode_debut;
        $campagne->periode_fin = $request->periode_fin;
        $campagne->prix_achat   = $request->prix_achat;
        $campagne->prime   = $request->prime;
        $campagne->save();
        $notify[] = ['success', isset($message) ? $message  : 'Campagne a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }

    public function travauxDangereuxIndex()
    {
        $pageTitle = "Manage Travaux Dangereux"; 
        $activeSettingMenu = 'travauxDangereux_settings';
        $travauxDangereux     = TravauxDangereux::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.travauxDangereux', compact('pageTitle', 'travauxDangereux','activeSettingMenu'));
    }

    public function travauxDangereuxStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
        ]);

        if ($request->id) {
            $travauxDangereux    = TravauxDangereux::findOrFail($request->id);
            $message = "Travaux Dangereux a été mise à jour avec succès.";
        } else {
            $travauxDangereux = new TravauxDangereux();
        } 
        $travauxDangereux->nom = trim($request->nom); 
        $travauxDangereux->save();
        $notify[] = ['success', isset($message) ? $message  : 'Travaux Dangereux a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }

    public function travauxLegersIndex()
    {
        $pageTitle = "Manage Travaux Legers"; 
        $activeSettingMenu = 'travauxLegers_settings';
        $travauxLegers     = TravauxLegers::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.travauxLegers', compact('pageTitle', 'travauxLegers','activeSettingMenu'));
    }

    public function travauxLegersStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
        ]);

        if ($request->id) {
            $travauxLegers    = TravauxLegers::findOrFail($request->id);
            $message = "Travaux Legers a été mise à jour avec succès.";
        } else {
            $travauxLegers = new TravauxLegers();
        } 
        $travauxLegers->nom = trim($request->nom); 
        $travauxLegers->save();
        $notify[] = ['success', isset($message) ? $message  : 'Travaux Legers a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }

    public function arretEcoleIndex()
    {
        $pageTitle = "Manage Arret Ecole"; 
        $activeSettingMenu = 'arretEcole_settings';
        $arretEcole     = ArretEcole::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.arretEcole', compact('pageTitle', 'arretEcole','activeSettingMenu'));
    }

    public function arretEcoleStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
        ]);

        if ($request->id) {
            $arretEcole    = ArretEcole::findOrFail($request->id);
            $message = "Arret Ecole a été mise à jour avec succès.";
        } else {
            $arretEcole = new ArretEcole();
        } 
        $arretEcole->nom = trim($request->nom); 
        $arretEcole->save();
        $notify[] = ['success', isset($message) ? $message  : 'Arret Ecole a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }

    public function typeFormationIndex()
    {
        $pageTitle = "Manage Type Formation"; 
        $activeSettingMenu = 'typeFormation_settings';
        $typeFormation     = TypeFormation::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.typeFormation', compact('pageTitle', 'typeFormation','activeSettingMenu'));
    }

    public function typeFormationStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
        ]);

        if ($request->id) {
            $typeFormation    = TypeFormation::findOrFail($request->id);
            $message = "Type Formation a été mise à jour avec succès.";
        } else {
            $typeFormation = new TypeFormation();
        } 
        $typeFormation->nom = trim($request->nom); 
        $typeFormation->save();
        $notify[] = ['success', isset($message) ? $message  : 'Type Formation a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }
    public function themeFormationIndex()
    {
        $pageTitle = "Manage theme de Formation"; 
        $activeSettingMenu = 'themeFormation_settings';
        $themeFormation     = ThemesFormation::with('typeFormation')->orderBy('id','desc')->paginate(getPaginate());
        $typeFormation = TypeFormation::get();
        return view('manager.config.themeFormation', compact('pageTitle', 'themeFormation','typeFormation','activeSettingMenu'));
    }

    public function themeFormationStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required',
            'typeformation'=>'required',
        ]);

        if ($request->id) {
            $themeFormation    = ThemesFormation::findOrFail($request->id);
            $message = "theme de Formation a été mise à jour avec succès.";
        } else {
            $themeFormation = new ThemesFormation();
        } 
        $themeFormation->nom = trim($request->nom); 
        $themeFormation->type_formation_id = $request->typeformation;
        $themeFormation->save();
        $notify[] = ['success', isset($message) ? $message  : 'theme de Formation a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }

    public function moduleFormationStaffIndex()
    {
        $pageTitle = "Manage Module de Formation Staff"; 
        $activeSettingMenu = 'moduleFormationStaff_settings';
        $moduleFormations     = ModuleFormationStaff::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.moduleFormationStaff', compact('pageTitle', 'moduleFormations','activeSettingMenu'));
    }

    public function moduleFormationStaffStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
        ]);

        if ($request->id) {
            $moduleFormationStaff    = ModuleFormationStaff::findOrFail($request->id);
            $message = "Module de Formation Staff a été mise à jour avec succès.";
        } else {
            $moduleFormationStaff = new ModuleFormationStaff();
        } 
        $moduleFormationStaff->nom = trim($request->nom); 
        $moduleFormationStaff->save();
        $notify[] = ['success', isset($message) ? $message  : 'Module de Formation Staff a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }

    public function themeFormationStaffIndex()
    {
        $pageTitle = "Manage theme de Formation Staff"; 
        $activeSettingMenu = 'themeFormationStaff_settings';
        $themeFormationStaff     = ThemeFormationStaff::with('moduleFormationStaff')->orderBy('id','desc')->paginate(getPaginate());
        $moduleFormationStaffs = ModuleFormationStaff::get();
        return view('manager.config.themeFormationStaff', compact('pageTitle', 'themeFormationStaff','moduleFormationStaffs','activeSettingMenu'));
    }

    public function themeFormationStaffStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required',
            'moduleFormationStaff'=>'required',
        ]);

        if ($request->id) {
            $themeFormationStaff    = ThemeFormationStaff::findOrFail($request->id);
            $message = "theme de Formation Staff a été mise à jour avec succès.";
        } else {
            $themeFormationStaff = new ThemeFormationStaff();
        } 
        $themeFormationStaff->nom = trim($request->nom); 
        $themeFormationStaff->module_formation_staff_id = $request->moduleFormationStaff;
        $themeFormationStaff->save();
        $notify[] = ['success', isset($message) ? $message  : 'theme de Formation Staff a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }

    public function categorieQuestionnaireIndex()
    {
        $pageTitle = "Manage Categorie Questionnaire"; 
        $activeSettingMenu = 'categorieQuestionnaire_settings';
        $categorieQuestionnaire     = CategorieQuestionnaire::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.categorieQuestionnaire', compact('pageTitle', 'categorieQuestionnaire','activeSettingMenu'));
    }

    public function categorieQuestionnaireStore(Request $request)
    {
        $request->validate([ 
            'titre'  => 'required', 
        ]);

        if ($request->id) {
            $categorieQuestionnaire    = CategorieQuestionnaire::findOrFail($request->id);
            $message = "Categorie Questionnaire a été mise à jour avec succès.";
        } else {
            $categorieQuestionnaire = new CategorieQuestionnaire();
        } 
        $categorieQuestionnaire->titre = trim($request->titre); 
        $categorieQuestionnaire->save();
        $notify[] = ['success', isset($message) ? $message  : 'Categorie Questionnaire a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }

    public function questionnaireIndex()
    {
        $pageTitle = "Manage Questionnaire"; 
        $activeSettingMenu = 'questionnaire_settings';
        $questionnaire     = Questionnaire::with('categorieQuestion')->orderBy('id','desc')->paginate(getPaginate());
        $categorieQuestion = CategorieQuestionnaire::get();
        return view('manager.config.questionnaire', compact('pageTitle', 'questionnaire','categorieQuestion','activeSettingMenu'));
    }

    public function questionnaireStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required',
            'categoriequestionnaire'=>'required',
        ]);

        if ($request->id) {
            $questionnaire    = Questionnaire::findOrFail($request->id);
            $message = "Questionnaire a été mise à jour avec succès.";
        } else {
            $questionnaire = new Questionnaire();
        } 
        $questionnaire->nom = trim($request->nom); 
        $questionnaire->categorie_questionnaire_id = $request->categoriequestionnaire;
        $questionnaire->save();
        $notify[] = ['success', isset($message) ? $message  : 'Questionnaire a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }
    public function especeArbreIndex()
    {
        $pageTitle = "Manage Espaces Arbres"; 
        $activeSettingMenu = 'especeArbre_settings';
        $especeArbre     = Agroespecesarbre::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.especeArbre', compact('pageTitle', 'especeArbre','activeSettingMenu'));
    }
    public function especeArbreStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
        ]);

        if ($request->id) {
            $especeArbre    = Agroespecesarbre::findOrFail($request->id);
            $message = "Espece Arbre a été mise à jour avec succès.";
        } else {
            $especeArbre = new Agroespecesarbre();
        } 
        $especeArbre->nom = trim($request->nom); 
        $especeArbre->strate = trim($request->strate); 
        $especeArbre->nom_scientifique = trim($request->nom_scientifique); 
        $especeArbre->save();
        $notify[] = ['success', isset($message) ? $message  : 'Espece Arbre a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }

    public function typeArchiveIndex()
    {
        $pageTitle = "Manage Type Archives"; 
        $activeSettingMenu = 'typeArchive_settings';
        $typeArchive     = TypeArchive::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.typeArchive', compact('pageTitle', 'typeArchive','activeSettingMenu'));
    }
    public function typeArchiveStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
        ]);

        if ($request->id) {
            $typeArchive    = TypeArchive::findOrFail($request->id);
            $message = "Espece Arbre a été mise à jour avec succès.";
        } else {
            $typeArchive = new TypeArchive();
        } 
        $typeArchive->nom = trim($request->nom); 
        $typeArchive->save();
        $notify[] = ['success', isset($message) ? $message  : 'Le contenu a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }
    public function instanceIndex()
    {
        $this->pageTitle = "Ajouter une instance";   
        return view('manager.config.create-instance-modal', $this->data);
    }
    public function instanceStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
        ]);

        if ($request->id) {
            $instance    = Instance::findOrFail($request->id);
            $message = "Espece Arbre a été mise à jour avec succès.";
        } else {
            $instance = new Instance();
        } 
        $instance->nom = trim($request->nom); 
        $instance->save();
       
        return Reply::successWithData(__('Le contenu a été ajouté avec succès.'), ['page_reload' => $request->page_reload]);
    }
    public function documentadIndex()
    {
        $this->pageTitle = "Ajouter un document administratif";   
        return view('manager.config.create-document-modal', $this->data);
    }
    public function documentadStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
        ]);

        if ($request->id) {
            $documentad    = Instance::findOrFail($request->id); 
        } else {
            $documentad = new DocumentAdministratif();
        } 
        $documentad->nom = trim($request->nom); 
        $documentad->save();
       
        return Reply::successWithData(__('Le contenu a été ajouté avec succès.'), ['page_reload' => $request->page_reload]);
    }
    public function entrepriseIndex()
    {
        $pageTitle = "Ajouter une entreprise"; 
        return view('manager.config.create-entreprise-modal', compact('pageTitle'));
    }
    public function entrepriseStore(Request $request)
    {
        $request->validate([ 
            'nom_entreprise'  => 'required',
            'telephone_entreprise'  => 'required',
            'adresse_entreprise'  => 'required',
            'email_entreprise'  => 'required|email|unique:entreprises,email_entreprise',
        ]);
        $entreprise = new Entreprise();
        $entreprise->nom = trim($request->nom); 
        $entreprise->telephone = trim($request->telephone);
        $entreprise->adresse = trim($request->adresse);
        $entreprise->email = trim($request->email);
        $entreprise->save();
        return Reply::successWithData(__('L\'entreprise a été ajouté avec succès.'), ['page_reload' => $request->page_reload]);
    }

    public function formateurStaffIndex()
    {
        $pageTitle = "Ajouter un formateur staff";   
        $entreprises = Entreprise::get();
        return view('manager.config.create-formateur-modal',compact('pageTitle','entreprises'));
    }
    public function formateurStaffStore(Request $request)
    {
        $request->validate([ 
            'nom_formateur'  => 'required',
            'entreprise_id'=>'required',
            'prenom_formateur'  => 'required',
            'telephone_formateur'  => 'required',
            'poste_formateur'  => 'required',
        ]);

        if ($request->id) {
            $formateurStaff    = FormateurStaff::findOrFail($request->id);
            $message = "Formateur Staff a été mise à jour avec succès.";
        } else {
            $formateurStaff = new FormateurStaff();
        } 
        $formateurStaff->entreprise_id = $request->entreprise_id;
        $formateurStaff->nom_formateur = trim($request->nom_formateur); 
        $formateurStaff->prenom_formateur = trim($request->prenom_formateur);
        $formateurStaff->telephone_formateur = trim($request->telephone_formateur);
        $formateurStaff->poste_formateur = trim($request->poste_formateur);
        
        $formateurStaff->save();
        return Reply::successWithData(__('Formateur Staff a été ajouté avec succès.'), ['page_reload' => $request->page_reload]);
        // $notify[] = ['success', isset($message) ? $message  : 'Formateur Staff a été ajouté avec succès.'];
        // return back()->withNotify($notify);
    }

    public function departementIndex()
    {
       $pageTitle = "Manage Départements"; 
        $activeSettingMenu = 'departement_settings';
        $departements     = Department::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.departement', compact('pageTitle', 'departements','activeSettingMenu'));
    }
    public function departementStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
        ]);

        if ($request->id) {
            $department    = Department::findOrFail($request->id);
            $message = "Le contenu a été mise à jour avec succès.";
        } else {
            $department = new Department();
        } 
        $department->cooperative_id = auth()->user()->cooperative_id;
        $department->department = trim($request->nom); 
        $department->save();
       
        $notify[] = ['success', isset($message) ? $message  : 'Le contenu a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }
    public function designationIndex()
    {
       $pageTitle = "Manage Désignations"; 
        $activeSettingMenu = 'designation_settings';
        $departements = Department::get();
        $designations     = Designation::orderBy('id','desc')->paginate(getPaginate());
        return view('manager.config.designation', compact('pageTitle', 'departements','designations','activeSettingMenu'));
    }
    public function designationStore(Request $request)
    {
        $request->validate([ 
            'nom'  => 'required', 
			'departement_id'  => 'required',
        ]);

        if ($request->id) {
            $designation    = Designation::findOrFail($request->id);
            $message = "Le contenu a été mise à jour avec succès.";
        } else {
            $designation = new Designation();
        } 
        $designation->cooperative_id = auth()->user()->cooperative_id;
        $designation->name = trim($request->nom); 
		$designation->parent_id = trim($request->departement_id); 
        $designation->save();
       
        $notify[] = ['success', isset($message) ? $message  : 'Le contenu a été ajouté avec succès.'];
        return back()->withNotify($notify);
    }
    public function campagneStatus($id)
    {
        return Campagne::changeStatus($id);
    }
    public function travauxDangereuxStatus($id)
    {
        return TravauxDangereux::changeStatus($id);
    }

    public function travauxLegersStatus($id)
    {
        return TravauxLegers::changeStatus($id);
    }
    public function arretEcoleStatus($id)
    {
        return ArretEcole::changeStatus($id);
    }
    public function typeFormationStatus($id)
    {
        return TypeFormation::changeStatus($id);
    }
    public function themeFormationStatus($id)
    {
        return ThemesFormation::changeStatus($id);
    }

    public function categorieQuestionnaireStatus($id)
    {
        return CategorieQuestionnaire::changeStatus($id);
    }

    public function questionnaireStatus($id)
    {
        return Questionnaire::changeStatus($id);
    }
    public function especeArbreStatus($id)
    {
        return Agroespecesarbre::changeStatus($id);
    }
    public function typeArchiveStatus($id)
    {
        return TypeArchive::changeStatus($id);
    }

    public function designationStatus($id)
    {
        return Designation::changeStatus($id);
    }
    public function departementStatus($id)
    {
        return Department::changeStatus($id);
    }
}
