<?php

namespace App\Http\Controllers\Manager;

use App\Models\Localite;
use App\Models\Partenaire;
use Illuminate\Http\Request;
use App\Models\ActionSociale;
use App\Models\AutreBeneficiaire;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\ActionSocialeLocalite;
use Illuminate\Support\Facades\Storage;
use Google\Service\CloudLifeSciences\Action;

class ActionSocialeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle      = "Gestion des Actions Sociales";
        $manager   = auth()->user();
        $actions = ActionSociale::dateFilter()->searchable([])->latest('id')->where('cooperative_id', $manager->cooperative_id)->where(function ($q) {
            if (request()->localite != null) {
                $q->where('localite_id', request()->localite);
            }
        })->with('cooperative')->paginate(getPaginate());

        return view('manager.action-sociale.index', compact('pageTitle', 'actions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Ajouter une Action Sociale";
        $manager = auth()->user();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->orderBy('nom')->get();

        return view('manager.action-sociale.create', compact('pageTitle', 'localites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRule = [
            'type_projet' => 'required',
            'titre_projet' => 'required',
            'description_projet' => 'required',
            'niveau_realisation' => 'required',
            'date_livraison' => 'required',
            'partenaires.*.partenaire' => 'required',
            'partenaires.*.type_partenaire' => 'required',
            'partenaires.*.montant_contribution' => 'required',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'documents_joints.*' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048'
        ];
        $request->validate($validationRule);
        if ($request->id) {
            $action = ActionSociale::find($request->id);
            $message = 'Action Sociale modifiée avec succès.';
        } else {
            $action = new ActionSociale();
            $action->code = $this->generateCode();
            $message = 'Action Sociale ajoutée avec succès.';
        }
        $action->type_projet = $request->type_projet;
        $action->titre_projet = $request->titre_projet;
        $action->description_projet = $request->description_projet;

        $action->niveau_realisation = $request->niveau_realisation;
        $action->date_demarrage = $request->date_demarrage;
        $action->date_fin_projet = $request->date_fin_projet;
        $action->cout_projet = $request->cout_projet;
        $action->date_livraison = $request->date_livraison;
        $action->commentaires = $request->commentaires;
        $action->cooperative_id = auth()->user()->cooperative_id;
        // if ($request->has('photos')) {
        //     $paths = [];
        //     foreach ($request->file('photos') as $photo) {
        //         try {
        //             $path = $photo->store('public/ActionSociales/photos');
        //             $paths[] = $path;
        //         } catch (\Exception $exp) {
        //             $notify[] = ['error', 'Impossible de télécharger votre image'];
        //             return back()->withNotify($notify);
        //         }
        //     }
        //     $action->photos = json_encode($paths);
        // }
        if ($request->has('photos')) {
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                try {
                    $originalName = $photo->getClientOriginalName();
                    $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                    $counter = 1;
                    while (Storage::exists('public/ActionSociales/photos/' . $originalName)) {
                        $originalName = $fileName . '_' . $counter . '.' . $extension;
                        $counter++;
                    }

                    $path = $photo->storeAs('public/ActionSociales/photos', $originalName);
                    $paths[] = $path;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Impossible de télécharger votre image'];
                    return back()->withNotify($notify);
                }
            }
            $action->photos = json_encode($paths);
        }
        if ($request->has('documents_joints')) {
            $paths = [];
            foreach ($request->file('documents_joints') as $document) {
                try {
                    $originalName = $document->getClientOriginalName();
                    $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                    $counter = 1;
                    while (Storage::exists('public/ActionSociales/documents/' . $originalName)) {
                        $originalName = $fileName . '_' . $counter . '.' . $extension;
                        $counter++;
                    }

                    $path = $document->storeAs('public/ActionSociales/documents', $originalName);
                    $paths[] = $path;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Impossible de télécharger votre image'];
                    return back()->withNotify($notify);
                }
            }
            $action->documents_joints = json_encode($paths);
        }
        $action->save();
        if ($action != null) {
            if ($request->partenaires != null && !collect($request->partenaires)->contains(null)) {
                Partenaire::where('action_sociale_id', $action->id)->delete();
                $data = [];
                foreach ($request->partenaires as $partenaire) {
                    $data[] = [
                        'action_sociale_id' => $action->id,
                        'partenaire' => $partenaire['partenaire'],
                        'type_partenaire' => $partenaire['type_partenaire'],
                        'montant' => $partenaire['montant_contribution']
                    ];
                }
                Partenaire::insert($data);
            }
            if ($request->beneficiaires_projet != null && !collect($request->beneficiaires_projet)->contains(null)) {
                ActionSocialeLocalite::where('action_sociale_id', $action->id)->delete();
                $data1 = [];
                foreach ($request->beneficiaires_projet as $beneficiaire) {
                    $data1[] = [
                        'action_sociale_id' => $action->id,
                        'localite_id' => $beneficiaire
                    ];
                }
                ActionSocialeLocalite::insert($data1);
            }
            if($request->autreBeneficiaire != null && !collect($request->autreBeneficiaire)->contains(null)) {
                AutreBeneficiaire::where('action_sociale_id', $action->id)->delete();
                $data2 = [];
                foreach ($request->autreBeneficiaire as $beneficiaire) {
                    $data2[] = [
                        'action_sociale_id' => $action->id,
                        'libelle' => $beneficiaire
                    ];
                }
                AutreBeneficiaire::insert($data2);
            }
            
           

        }

        $notify[] = ['success', isset($message) ? $message : 'Action Sociale ajoutée avec succès.'];
        return back()->withNotify($notify);
    }

    private function generateCode()
    {
        static $number = 0;
        $number++;
        $year = date('Y');
        return sprintf('CR-AS-%s-%03d', $year, $number);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageTitle = "Détail Action Sociale";
        $actionSociale = ActionSociale::find($id); // Remplacez ActionSociale par le nom de votre modèle
        $partenaires = $actionSociale->partenaires;
        return view('manager.action-sociale.show', compact('actionSociale', 'pageTitle', 'partenaires'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = "Modifier une Action Sociale";
        $actionSociale = ActionSociale::find($id); // Remplacez ActionSociale par le nom de votre modèle
        $partenaires = $actionSociale->partenaires;
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $actionSociale->cooperative_id], ['localites.status', 1]])->orderBy('nom')->get();
        $dataLocalite = ActionSocialeLocalite::where('action_sociale_id', $id)->pluck('localite_id')->toArray();
        return view('manager.action-sociale.edit', compact('actionSociale', 'pageTitle', 'partenaires', 'localites', 'dataLocalite'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActionSociale $action)
    {
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
