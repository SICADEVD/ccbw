<?php

namespace App\Http\Controllers\Manager;

use App\Models\Menage;
use App\Models\Section;
use App\Models\Localite;
use App\Constants\Status;
use App\Models\Producteur;
use App\Models\Cooperative;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ExportMenages;
use App\Rules\VlidateEnfantTotal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreMenageRequest;

class MenageController extends Controller
{

    public function index()
    {
        $pageTitle = "Gestion des ménages";
        $manager = auth()->user();
        $cooperative = Cooperative::with('sections.localites')->find($manager->cooperative_id);
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $menages = Menage::dateFilter()->searchable([
            "quartier", "sources_energies", "boisChauffe", "ordures_menageres",
            "separationMenage", "eauxToillette", "eauxVaisselle", "wc",
            "menages.sources_eaux", "type_machines", "garde_machines", "equipements",
            "traitementChamps", "activiteFemme", "nomActiviteFemme", "champFemme", "nombreHectareFemme"
        ])->latest('id')
            ->joinRelationship('producteur.localite.section')
            ->where('cooperative_id', $manager->cooperative_id)
            ->where(function ($q) {
                if (request()->localite != null) {
                    $q->where('localite_id', request()->localite);
                }
            })
            ->with(['producteur.localite', 'producteur.localite.section']) // Charger les relations "localite" et "section" des producteurs
            ->paginate(getPaginate());

        return view('manager.menage.index', compact('pageTitle', 'menages', 'localites'));
    }

    public function create()
    {
        $pageTitle = "Ajouter un ménage";
        $manager = auth()->user();
        $cooperative = Cooperative::with('sections.localites', 'sections.localites.section')->find($manager->cooperative_id);
        $sections = $cooperative->sections;
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $producteurs = Producteur::with('localite')->get();

        return view('manager.menage.create', compact('pageTitle', 'producteurs', 'sections', 'localites'));

    }

    public function store(Request $request)
    {
        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivé'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->id) {
            $menage = Menage::findOrFail($request->id);
            $rules = [
                'producteur_id'    => 'required|exists:producteurs,id',
                'quartier' => 'required|max:255',
                'ageEnfant0A5' => ['required','integer', new VlidateEnfantTotal],
                'ageEnfant6A17' => ['required','integer', new VlidateEnfantTotal],
                'enfantscolarises' => ['required','integer', new VlidateEnfantTotal],
                'enfantsPasExtrait' => ['required','integer', new VlidateEnfantTotal],
                'enfantsPasExtrait6A17' => ['required','integer'],
                'sources_energies'  => 'required|max:255',
                'ordures_menageres'  => 'required|max:255',
                'separationMenage'  => 'required|max:255',
                'eauxToillette'  => 'required|max:255',
                'eauxVaisselle'  => 'required|max:255',
                'wc'  => 'required|max:255',
                'sources_eaux'  => 'required|max:255',
                'equipements'  => 'required|max:255',
                'traitementChamps'  => 'required|max:255',
                'activiteFemme'  => 'required|max:255',
                'nomApplicateur'=>'required_if:traitementChamps,==,non',
                'numeroApplicateur' => 'required_if:traitementChamps,non|regex:/^\d{10}$/|nullable|unique:menages,numeroApplicateur,'.$request->id,
            ];
            $attributes = [
                'producteur' => 'Producteur',

            ];
            $messages = [
                'producteur.required' => 'Le champ producteur est obligatoire',
                'quartier.required' => 'Le champ quartier est obligatoire',
                'ageEnfant0A5.required' => 'Le champ ageEnfant0A5 est obligatoire',
                'ageEnfant6A17.required' => 'Le champ ageEnfant6A17 est obligatoire',
                'enfantscolarises.required' => 'Le champ enfantscolarises est obligatoire',
                'enfantsPasExtrait.required' => 'Le champ enfantsPasExtrait est obligatoire',
                'sources_energies.required' => 'Le champ sources_energies est obligatoire',
                'ordures_menageres.required' => 'Le champ ordures_menageres est obligatoire',
                'separationMenage.required' => 'Le champ separationMenage est obligatoire',
                'eauxToillette.required' => 'Le champ eauxToillette est obligatoire',
                'eauxVaisselle.required' => 'Le champ eauxVaisselle est obligatoire',
                'wc.required' => 'Le champ wc est obligatoire',
                'sources_eaux.required' => 'Le champ sources_eaux est obligatoire',
                'garde_machines.required' => 'Le champ garde_machines est obligatoire',
                'equipements.required' => 'Le champ equipements est obligatoire',
                'traitementChamps.required' => 'Le champ traitementChamps est obligatoire',
                'activiteFemme.required' => 'Le champ activitFemme est obligatoire',
            ];
            $this->validate($request, $rules, $messages, $attributes);
            $message = "Le menage a été mise à jour avec succès";
        } else {
            $menage = new Menage();
            $rules = [
                'producteur_id'    => 'required|exists:producteurs,id',
                'quartier' => 'required|max:255',
                'ageEnfant0A5' => ['required','integer', new VlidateEnfantTotal],
                'ageEnfant6A17' => ['required','integer', new VlidateEnfantTotal],
                'enfantscolarises' => ['required','integer', new VlidateEnfantTotal],
                'enfantsPasExtrait' => ['required','integer', new VlidateEnfantTotal],
                'enfantsPasExtrait6A17' => ['required','integer'],
                'sources_energies'  => 'required|max:255',
                'ordures_menageres'  => 'required|max:255',
                'separationMenage'  => 'required|max:255',
                'eauxToillette'  => 'required|max:255',
                'eauxVaisselle'  => 'required|max:255',
                'wc'  => 'required|max:255',
                'sources_eaux'  => 'required|max:255',
                'equipements'  => 'required|max:255',
                'traitementChamps'  => 'required|max:255',
                'activiteFemme'  => 'required|max:255',
                'nomApplicateur'=>'required_if:traitementChamps,==,non',
                'numeroApplicateur'=>'required_if:traitementChamps,==,non|regex:/^\d{10}$/|nullable|unique:menages,numeroApplicateur',
            ];
            $attributes = [
                'producteur' => 'Producteur',

            ];
            $messages = [
                'producteur.required' => 'Le champ producteur est obligatoire',
                'quartier.required' => 'Le champ quartier est obligatoire',
                'ageEnfant0A5.required' => 'Le champ ageEnfant0A5 est obligatoire',
                'ageEnfant6A17.required' => 'Le champ ageEnfant6A17 est obligatoire',
                'enfantscolarises.required' => 'Le champ enfantscolarises est obligatoire',
                'enfantsPasExtrait.required' => 'Le champ enfantsPasExtrait est obligatoire',
                'sources_energies.required' => 'Le champ sources_energies est obligatoire',
                'ordures_menageres.required' => 'Le champ ordures_menageres est obligatoire',
                'separationMenage.required' => 'Le champ separationMenage est obligatoire',
                'eauxToillette.required' => 'Le champ eauxToillette est obligatoire',
                'eauxVaisselle.required' => 'Le champ eauxVaisselle est obligatoire',
                'wc.required' => 'Le champ wc est obligatoire',
                'sources_eaux.required' => 'Le champ sources_eaux est obligatoire',
                'garde_machines.required' => 'Le champ garde_machines est obligatoire',
                'equipements.required' => 'Le champ equipements est obligatoire',
                'traitementChamps.required' => 'Le champ traitementChamps est obligatoire',
                'activiteFemme.required' => 'Le champ activitFemme est obligatoire',
            ];
            $this->validate($request, $rules, $messages, $attributes);
            $message = "Le menage a été crée avec succès";
        }
        if ($menage->producteur_id != $request->producteur_id) {
            $hasMenage = Menage::where('producteur_id', $request->producteur)->exists();
            if ($hasMenage) {
                $notify[] = ['error', 'Ce producteur a déjà un menage enregistré'];
                return back()->withNotify($notify)->withInput();
            }
        }
        $menage->producteur_id  = $request->producteur_id;
        $menage->quartier  = $request->quartier;
        $menage->ageEnfant0A5  = $request->ageEnfant0A5;
        $menage->ageEnfant6A17  = $request->ageEnfant6A17;
        $menage->enfantscolarises  = $request->enfantscolarises;
        $menage->enfantsPasExtrait = $request->enfantsPasExtrait;
        $menage->enfantsPasExtrait6A17 = $request->enfantsPasExtrait6A17;
        $menage->sources_energies  = $request->sources_energies;
        $menage->boisChauffe     = $request->boisChauffe;
        $menage->ordures_menageres    = $request->ordures_menageres;
        $menage->separationMenage = $request->separationMenage;
        $menage->eauxToillette    = $request->eauxToillette;
        $menage->eauxVaisselle    = $request->eauxVaisselle;
        $menage->wc    = $request->wc;
        $menage->sources_eaux    = $request->sources_eaux;
        $menage->type_machines    = $request->type_machines;
        $menage->garde_machines    = $request->garde_machines;
        $menage->equipements    = $request->equipements;
        $menage->traitementChamps    = $request->traitementChamps;
        $menage->nomApplicateur   = $request->nomApplicateur;
        $menage->numeroApplicateur   = $request->numeroApplicateur;
        $menage->activiteFemme    = $request->activiteFemme;
        $menage->nomActiviteFemme    = $request->nomActiviteFemme;
        $menage->champFemme    = $request->champFemme;
        $menage->nombreHectareFemme    = $request->nombreHectareFemme;
        $menage->autreMachine    = $request->autreMachine;
        $menage->autreEndroit    = $request->autreEndroit;
        $menage->userid = auth()->user()->id;
        $menage->autreSourceEau   = $request->autreSourceEau;
        $menage->etatAutreMachine   = $request->etatAutreMachine;
        $menage->etatatomiseur   = $request->etatatomiseur;
        dd(json_encode($request->all()));
        $menage->save();
        $notify[] = ['success', isset($message) ? $message : 'Le menage a été crée avec succès.'];
        return back()->withNotify($notify);
    }


    public function edit($id)
    {
        $pageTitle = "Mise à jour de le menage";
        $manager   = auth()->user();
        $cooperative = Cooperative::with('sections.localites', 'sections.localites.section')->find($manager->cooperative_id);
        $localites = $cooperative->sections->flatMap->localites->filter(function ($localite) {
            return $localite->active();
        });
        $producteurs  = Producteur::with('localite')->get();
        $sections = $cooperative->sections;
        $menage   = Menage::findOrFail($id);
        return view('manager.menage.edit', compact('pageTitle', 'localites', 'menage', 'producteurs','sections'));
    }

    public function status($id)
    {
        return Menage::changeStatus($id);
    }

    public function exportExcel()
    {
        return (new ExportMenages())->download('menages.xlsx');
    }
}
