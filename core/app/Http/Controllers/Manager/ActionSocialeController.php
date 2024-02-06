<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use App\Models\ActionSociale;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use Illuminate\Support\Facades\File;
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
        

        try {
            if ($request->hasFile('photos')) {
                $photos = $request->file('photos');

                // Parcourez chaque fichier
                foreach ($photos as $photo) {
                    // Générez un nouveau nom pour le fichier
                    $filename = time() . '_' . $photo->getClientOriginalName();

                    // Vérifiez si le répertoire existe, sinon créez-le
                    $path = public_path('ActionSociales/photos');
                    if (!File::exists($path)) {
                        File::makeDirectory($path, 0777, true);
                    }

                    // Déplacez le fichier dans le répertoire
                    $photo->move($path, $filename);
                }
            }
            // Continuez avec le reste de votre méthode store...
        } catch (\Exception $e) {
            // Gérez l'erreur, par exemple en renvoyant un message d'erreur à l'utilisateur
            $notify[] = ['error', 'Une erreur s\'est produite lors du téléchargement des photos : ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
        try {
            // Vérifiez si des fichiers ont été téléchargés
            if ($request->hasFile('documents_joints')) {

                $documents = $request->file('documents_joints');

                foreach ($documents as $document) {
                    // Générez un nouveau nom pour le fichier
                    $filename = time() . '_' . $document->getClientOriginalName();

                    // Vérifiez si le répertoire existe, sinon créez-le
                    $path = public_path('ActionSociales/documents');
                    if (!File::exists($path)) {
                        File::makeDirectory($path, 0777, true);
                    }

                    $document->move($path, $filename);
                }
            }
        } catch (\Exception $e) {
            // Gérez l'erreur, par exemple en renvoyant un message d'erreur à l'utilisateur
            $notify[] = ['error', 'Une erreur s\'est produite lors du téléchargement des photos : ' . $e->getMessage()];
            return back()->withNotify($notify);
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
        //
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
        return view('manager.action-sociale.edit', compact('actionSociale', 'pageTitle'));
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
