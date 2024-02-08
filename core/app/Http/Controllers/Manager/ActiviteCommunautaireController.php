<?php

namespace App\Http\Controllers\Manager;

use App\Models\Localite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActiviteCommunautaire;
use Illuminate\Support\Facades\Storage;
use App\Models\ActiviteCommunautaireLocalite;

class ActiviteCommunautaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle      = "Gestion des Activités Communautaires";
        $manager   = auth()->user(); 
        $activites = ActiviteCommunautaire::dateFilter()->searchable([])->latest('id')->where('cooperative_id', $manager->cooperative_id)->where(function ($q) {
            if (request()->localite != null) {
                $q->where('localite_id', request()->localite);
            }
        })->with('cooperative')->paginate(getPaginate());

        return view('manager.activite-communautaire.index', compact('pageTitle', 'activites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Ajouter une Activité Communautaire";
        $manager = auth()->user(); 
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->orderBy('nom')->get();
       
        return view('manager.activite-communautaire.create', compact('pageTitle','localites'));
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
            'titre_projet' => 'required',
            'description_projet' => 'required',
            'type_projet' => 'required',
            'niveau_realisation' => 'required',
            'cout_projet' => 'required',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'documents_joints.*' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048'
        ];
        $request->validate($validationRule);
        if($request->id){
            $communaute = ActiviteCommunautaire::find($request->id);
            $message = "Activité Communautaire modifiée avec succès";
        }
        else{
            $communaute = new ActiviteCommunautaire();
            $communaute->code = $this->generateCode();
            $message = "Activité Communautaire ajoutée avec succès";
        }
        $communaute->titre_projet = $request->titre_projet;
        $communaute->description_projet = $request->description_projet;
        $communaute->type_projet = $request->type_projet;
        $communaute->niveau_realisation = $request->niveau_realisation;
        $communaute->cout_projet = $request->cout_projet;
        $communaute->cooperative_id = auth()->user()->cooperative_id;
        $communaute->localite_id = $request->localite_id;
        $communaute->commentaires = $request->commentaires;
        $communaute->date_livraison = $request->date_livraison;
        $communaute->date_demarrage = $request->date_demarrage;
        $communaute->date_fin_projet = $request->date_fin_projet;
        $communaute->date_demarrage = $request->date_demarrage;
        $communaute->liste_beneficiaires = $request->liste_beneficiaires;
        // $communaute->localite_projet = $request->localite_projet;
        if ($request->has('photos')) {
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                try {
                    $directory = 'public/ActiviteCommunautaires/photos';
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
            $communaute->photos = json_encode($paths);
        }
        if ($request->has('documents_joints')) {
            $paths = [];
            foreach ($request->file('documents_joints') as $document) {
                try {
                    $directory = 'public/ActiviteCommunautaires/documents';
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
            $communaute->documents_joints = json_encode($paths);
        }
        $communaute->save();
        if($communaute != null){
            $id = $communaute->id;
            ActiviteCommunautaireLocalite::where('activite_communautaire_id', $id)->delete();
            if($request->localite != null && !collect($request->localite)->contains(null)){
                foreach($request->localite as $localite){
                    $communauteLocalite = new ActiviteCommunautaireLocalite();
                    $communauteLocalite->activite_communautaire_id = $id;
                    $communauteLocalite->localite_id = $localite;
                    $communauteLocalite->save();
                }
            }
        }
        $notify[] = ['success', isset($message) ? $message : 'Activité Communautaire ajoutée avec succès.'];
        return back()->withNotify($notify);
        
    }
    private function generateCode(){
        static $nbr = 0;
        $nbr++;
        $year = date('Y');
        return sprintf('CR-AC-%s-%03d', $year, $nbr);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageTitle = "Détails de l'Activité Communautaire";
        $manager = auth()->user(); 
        $communauteSociale = ActiviteCommunautaire::find($id); // Remplacez ActionSociale par le nom de votre modèle
        $dataLocalite = ActiviteCommunautaireLocalite::where('activite_communautaire_id', $id)->pluck('localite_id')->toArray();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->orderBy('nom')->get();
        return view('manager.activite-communautaire.show', compact('pageTitle','communauteSociale','dataLocalite','localites'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = "Modifier une Activité Communautaire";
        $manager = auth()->user(); 
        $communauteSociale = ActiviteCommunautaire::find($id); // Remplacez ActionSociale par le nom de votre modèle
        $dataLocalite = ActiviteCommunautaireLocalite::where('activite_communautaire_id', $id)->pluck('localite_id')->toArray();
        $localites = Localite::joinRelationship('section')->where([['cooperative_id', $manager->cooperative_id], ['localites.status', 1]])->orderBy('nom')->get();
        return view('manager.activite-communautaire.edit', compact('localites','pageTitle','communauteSociale','dataLocalite'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
