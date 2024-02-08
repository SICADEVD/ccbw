<?php

namespace App\Http\Controllers\Manager;

use App\Models\Partenaire;
use Illuminate\Http\Request;
use App\Models\ActionSociale;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
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

        return view('manager.action-sociale.create', compact('pageTitle'));
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
            'beneficiaires_projet' => 'required',
            'niveau_realisation' => 'required',
            'partenaires.*.partenaire' => 'required',
            'partenaires.*.type_partenaire' => 'required',
            'partenaires.*.montant_contribution' => 'required',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'documents_joints.*' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048'
        ];
        $request->validate($validationRule);
        
        if($request->id){
            $action = ActionSociale::find($request->id);
            $message = 'Action Sociale modifiée avec succès.';
        }
        else{
            $action = new ActionSociale();
            $message = 'Action Sociale ajoutée avec succès.';
        }
        $action->type_projet = $request->type_projet;
        $action->titre_projet = $request->titre_projet;
        $action->description_projet = $request->description_projet;
        $action->beneficiaires_projet = $request->beneficiaires_projet;

        $action->niveau_realisation = $request->niveau_realisation;
        $action->date_demarrage = $request->date_demarrage;
        $action->date_fin_projet = $request->date_fin_projet;
        $action->cout_projet = $request->cout_projet;
        $action->date_livraison = $request->date_livraison;
        $action->commentaires = $request->commentaires;
        $action->cooperative_id = auth()->user()->cooperative_id;
        if ($request->has('photos')) {
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                try {
                    $directory = 'public/ActionSociales/photos';
                    if (!Storage::exists($directory)) {
                        Storage::makeDirectory($directory);
                    }
                    $path = $photo->store($directory);
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
                    $directory = 'public/ActionSociales/documents';
                    if (!Storage::exists($directory)) {
                        Storage::makeDirectory($directory);
                    }
                    $path = $document->store($directory);
                    $paths[] = $path;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Impossible de télécharger votre image'];
                    return back()->withNotify($notify);
                }
            }
            $action->documents_joints = json_encode($paths);
        }
        $action->save();
        if($action != null){
            if($request->partenaires != null && !collect($request->partenaires)->contains(null)){
                Partenaire::where('action_sociale_id', $action->id)->delete();
                $data = [];
                foreach($request->partenaires as $partenaire){
                    $data[] = [
                        'action_sociale_id' => $action->id,
                        'partenaire' => $partenaire['partenaire'],
                        'type_partenaire' => $partenaire['type_partenaire'],
                        'montant'=> $partenaire['montant_contribution']
                    ];
                }
            }
            Partenaire::insert($data);
        }

        $notify[] = ['success', isset($message) ? $message : 'Action Sociale ajoutée avec succès.'];
        return back()->withNotify($notify);
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
        return view('manager.action-sociale.show', compact('actionSociale', 'pageTitle','partenaires'));
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
        return view('manager.action-sociale.edit', compact('actionSociale', 'pageTitle','partenaires'));
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
