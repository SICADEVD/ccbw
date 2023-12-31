<?php

namespace App\Http\Controllers\Manager;

use Excel;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Campagne;
use App\Models\Localite;
use App\Constants\Status;
use App\Models\Producteur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TypeFormation;
use App\Models\SuiviFormation;
use App\Models\ThemeSousTheme;
use App\Models\ThemesFormation;
use App\Exports\ExportFormations;
use App\Models\SousThemeFormation;
use App\Models\TypeFormationTheme;
use Illuminate\Support\Facades\DB;
use App\Models\SuiviFormationTheme;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\SuiviFormationVisiteur;
use App\Models\SuiviFormationProducteur;
use Illuminate\Support\Arr;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\Theme;

class FormationController extends Controller
{

    public function index()
    {
        $pageTitle      = "Gestion des formations";
        $manager   = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->get();
        $modules = TypeFormation::active()->get();
        $formations = SuiviFormation::dateFilter()->searchable(['lieu_formation'])->latest('id')->joinRelationship('localite.section')->where('sections.cooperative_id', $manager->cooperative_id)->where(function ($q) {
            if (request()->localite != null) {
                $q->where('localite_id', request()->localite);
            }
            if (request()->module != null) {
                $q->where('type_formation_id', request()->module);
            }
        })->with('localite', 'campagne', 'typeFormation', 'user')->paginate(getPaginate());

        return view('manager.formation.index', compact('pageTitle', 'formations', 'localites', 'modules'));
    }

    public function create()
    {
        $pageTitle = "Ajouter un formation";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->get();
        $typeformations  = TypeFormation::all();
        $themes  = ThemesFormation::with('typeFormation')->get();
        $sousThemes  = SousThemeFormation::with('themeFormation')->get();
        $staffs  = User::staff()->get();
        return view('manager.formation.create', compact('pageTitle', 'producteurs', 'localites', 'typeformations', 'themes', 'staffs', 'sousThemes'));
    }

    public function store(Request $request)
    {

        $validationRule = [
            'localite'    => 'required|exists:localites,id',
            'staff' => 'required|exists:users,id',
            'producteur' => 'required|max:255',
            'lieu_formation'  => 'required|max:255',
            'formation_type'  => 'required|max:255',
            'duree_formation' => 'required|date_format:H:i',
        ];

        $request->validate($validationRule);

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivé'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->id) {
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
        $formation->duree_formation = $request->duree_formation;
        $formation->observation_formation = $request->observation_formation;
        $formation->formation_type = $request->formation_type;
        $formation->date_debut_formation = $request->multiStartDate;
        $formation->date_fin_formation = $request->multiEndDate;
        $formation->userid = auth()->user()->id;

        if ($request->hasFile('photo_formation')) {
            try {
                $formation->photo_formation = $request->file('photo_formation')->store('public/formations');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('rapport_formation')) {
            try {
                $formation->rapport_formation = $request->file('rapport_formation')->store('public/formations');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
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
        $notify[] = ['success', isset($message) ? $message : 'Le formation a été crée avec succès.'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Mise à jour de la formation";
        $manager   = auth()->user();
        $producteurs  = Producteur::with('localite')->get();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->get();
        $formation   = SuiviFormation::findOrFail($id);
        $typeformations  = TypeFormation::all();

        $modules = array();
        $themesSelected = array();
        $sousThemesSelected = array();
        $producteursSelected = array();
        
        foreach ($formation->formationProducteur as $item) {
            $producteursSelected[] = $item->producteur_id;
        }
        foreach ($formation->typeFormationTheme as $item) {
            $modules[] = $item->type_formation_id; 
            $themesSelected[] = $item->theme_formation_id;
        }
        
        foreach ($formation->themeSousTheme as $item) {
            $sousThemesSelected[] = $item->sous_theme_id;
        }

        $themes  = ThemesFormation::with('typeFormation')->get();
        $sousthemes  = SousThemeFormation::with('themeFormation')->get();
        $staffs  = User::staff()->get();
        
        $dataProducteur = $dataVisiteur = $dataTheme = array();

        return view('manager.formation.edit', compact('pageTitle', 'localites', 'formation', 'producteurs', 'typeformations', 'themes', 'staffs', 'dataProducteur', 'dataTheme', 'modules', 'themesSelected', 'sousthemes', 'sousThemesSelected', 'producteursSelected'));
    }

    public function visiteur($id)
    {
        $pageTitle = "Gestion des visiteurs";
        $manager   = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->get();
        $modules = TypeFormation::active()->get();

        $visiteurs = SuiviFormationVisiteur::dateFilter()->searchable(['suivi_formation_visiteurs.nom', 'suivi_formation_visiteurs.prenom'])->latest('suivi_formation_visiteurs.id')->joinRelationship('suiviFormation.localite.section')
            ->where('sections.cooperative_id', $manager->cooperative_id)
            ->where(function ($q) use ($id) {
                if (request()->localite != null) {
                    $q->where('localite_id', request()->localite);
                }

                if (request()->module != null) {
                    $q->where('type_formation_id', request()->module);
                }

                if ($id != null) {
                    $q->where('suivi_formation_visiteurs.suivi_formation_id', $id);
                }
            })
            ->with('suiviFormation')
            ->paginate(getPaginate());


        return view('manager.formation.visiteur', compact('pageTitle', 'visiteurs', 'id', 'localites', 'modules'));
    }

    public function createvisiteur($id)
    {
        $pageTitle = "Ajouter un visiteur";
        $formation   = SuiviFormation::findOrFail($id);
        $localite = Localite::where('id', $formation->localite_id)->first();
        $idLocalite = $localite->id;
        $producteurs = Producteur::where('localite_id', $localite->id)->get();
        return view('manager.formation.visiteurcreate', compact('pageTitle', 'producteurs', 'id', 'idLocalite'));
    }
    public function editvisiteur($id)
    {
        $pageTitle = "Mise à jour du visiteur";
        $visiteur   = SuiviFormationVisiteur::findOrFail(request()->id);
        $localite = Localite::where('id', $visiteur->suiviFormation->localite_id)->first();
        $idLocalite = $localite->id;
        $producteurs = Producteur::where('localite_id', $localite->id)->get();
        return view('manager.formation.visiteuredit', compact('pageTitle', 'producteurs', 'idLocalite', 'visiteur'));
    }
    public function storevisiteur(Request $request)
    {
        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivé'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->id) {
            $validationRule = [
                'nom'  => 'required|max:255',
                'prenom'  => 'required|max:255',
                'sexe'  => 'required|max:255',
                'telephone'  => 'required|regex:/^\d{10}$/|unique:suivi_formation_visiteurs,telephone,' . $request->id,
            ];
            $visiteur = SuiviFormationVisiteur::findOrFail($request->id);
            $message = "La formation a été mise à jour avec succès";
        } else {
            $validationRule = [
                'nom'  => 'required|max:255',
                'prenom'  => 'required|max:255',
                'sexe'  => 'required|max:255',
                'telephone'  => 'required|regex:/^\d{10}$/|unique:suivi_formation_visiteurs,telephone',
            ];

            $visiteur = new SuiviFormationVisiteur();
        }
        $request->validate($validationRule);

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
        $notify[] = ['success', isset($message) ? $message : 'Le visiteur a été crée avec succès.'];
        return back()->withNotify($notify);
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
