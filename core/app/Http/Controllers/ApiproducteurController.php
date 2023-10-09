<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Constants\Status;
use App\Models\Producteur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Producteur_info;
use Illuminate\Validation\Rule;
use App\Models\Infos_producteur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreInfoRequest;
use App\Models\Producteur_infos_mobile;
use App\Models\Producteur_infos_typeculture;
use App\Http\Requests\UpdateProducteurRequest;
use App\Models\Producteur_infos_maladieenfant;
use Illuminate\Validation\ValidationException;
use App\Models\Producteur_infos_autresactivite;

class ApiproducteurController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {

    // 
  }

  public function getproducteurs(Request $request)
  {
    $userid = $request->userid;
    // $producteur = array();
    // $localite = DB::table('user_localites as rl')->join('localites as l', 'rl.localite_id','=','l.id')->where('user_id', $input['userid'])->select('l.id')->get();
    // if(isset($localite) && count($localite)){
    //     foreach($localite as $data){
    //         $idlocal[] = $data->id;
    //       }

    //       $localites=implode(',',$idlocal); 

    //       $producteur = Producteur::select('id','nom','prenoms','codeProdapp as codeProd','localite_id')->whereIn('localite_id', $idlocal)->get(); 

    // }

    $producteurs = Producteur::join('localites', 'producteurs.localite_id', '=', 'localites.id')
      ->where('producteurs.userid', $userid)
      ->select('producteurs.nom', 'producteurs.prenoms', 'localites.section_id as section_id', 'localites.id as localite_id', 'producteurs.id as id', 'producteurs.codeProd as codeProd')
      ->get();


    return response()->json($producteurs, 201);
  }
  //creation de getstaff(elle retourne les staff d'une cooperative donnée)
  public function getstaff(Request $request)
  {

    $cooperativeId = $request->cooperative_id;
    $roleName = $request->role_name;
    $staffs = User::whereHas(
      'roles',
      function ($q) use ($roleName) {
        $q->where('name', $roleName);
      }
    )
      ->where('cooperative_id', $cooperativeId)
      ->select('id', 'firstname', 'lastname', 'username', 'email', 'mobile', 'adresse')
      ->get();

    return response()->json($staffs, 201);
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {

    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if ($request->id) {
      $producteur = Producteur::findOrFail($request->id);
      $validationRule = [
        'programme_id' => 'required|exists:programmes,id',
            'proprietaires' => 'required',
            'certificats' => 'required',
            'variete' => 'required',
            'habitationProducteur' => 'required',
            'statut' => 'required',
            'statutMatrimonial' => 'required',
            'localite_id'    => 'required|exists:localites,id',
            'nom' => 'required|max:255',
            'prenoms'  => 'required|max:255',
            'sexe'  => 'required|max:255',
            'nationalite'  => 'required|max:255',
            'dateNaiss'  => 'required|max:255',
            'phone1'  => 'required|max:255',
            'niveau_etude'  => 'required|max:255',
            'type_piece'  => 'required|max:255',
            'numPiece'  => 'required|max:255',
            'anneeDemarrage' =>'required_if:proprietaires,==,Garantie',
            'anneeFin' =>'required_if:proprietaires,==,Garantie',
            'plantePartage'=>'required_if:proprietaires,==,Planté-partager',
            'typeCarteSecuriteSociale'=>'required',
            'autreCertificats'=>'required_if:certificats,==,Autre',
            'autreVariete'=>'required_if:variete,==,Autre',
            'codeProd'=>'required_if:statut,==,Certifie',
            'certificat'=>'required_if:statut,==,Certifie',
            'phone2'=>'required_if:autreMembre,==,oui',
            'autrePhone'=>'required_if:autreMembre,==,oui',
            'numCMU'=>'required_if:carteCMU,==,oui',
            'num_ccc' => ['max:20', Rule::unique('producteurs', 'num_ccc')->ignore($producteur)],
      ];
      $messages = [
        'programme_id.required' => 'Le programme est obligatoire',
        'proprietaires.required' => 'Le type de propriétaire est obligatoire',
        'certificats.required' => 'Le type de certificat est obligatoire',
        'variete.required' => 'Le type de variété est obligatoire',
        'habitationProducteur.required' => 'Le type d\'habitation est obligatoire',
        'statut.required' => 'Le statut est obligatoire',
        'statutMatrimonial.required' => 'Le statut matrimonial est obligatoire',
        'localite_id.required' => 'La localité est obligatoire',
        'nom.required' => 'Le nom est obligatoire',
        'prenoms.required' => 'Le prénom est obligatoire',
        'sexe.required' => 'Le sexe est obligatoire',
        'nationalite.required' => 'La nationalité est obligatoire',
        'dateNaiss.required' => 'La date de naissance est obligatoire',
        'phone1.required' => 'Le numéro de téléphone est obligatoire',
        'niveau_etude.required' => 'Le niveau d\'étude est obligatoire',
        'type_piece.required' => 'Le type de pièce est obligatoire',
        'numPiece.required' => 'Le numéro de pièce est obligatoire',
        'num_ccc.unique' => 'Le numéro de CCC existe déjà',
        'anneeDemarrage.required_if' => 'L\'année de démarrage est obligatoire',
        'anneeFin.required_if' => 'L\'année de fin est obligatoire',
        'plantePartage.required_if' => 'Le type de plante est obligatoire',
        'typeCarteSecuriteSociale.required' => 'Le type de carte de sécurité sociale est obligatoire',
        'autreCertificats.required_if' => 'Le type de certificat est obligatoire',
        'autreVariete.required_if' => 'Le type de variété est obligatoire',
        'codeProdapp.required_if' => 'Le code Prodapp est obligatoire',
        'certificat.required_if' => 'Le certificat est obligatoire',
        'phone2.required_if' => 'Le numéro de téléphone est obligatoire',
        'autrePhone.required_if' => 'Le champ membre de famille est obligatoire',
      ];
      $request->validate($validationRule, $messages);
      if ($request->picture) {
        $image = $request->picture;
        $image = Str::after($image, 'base64,');
        $image = str_replace(' ', '+', $image);
        $imageName = (string) Str::uuid() . '.' . 'jpg';
        File::put(storage_path() . "/app/public/producteurs/pieces/" . $imageName, base64_decode($image));
        $picture = "public/producteurs/pieces/$imageName";
        $input['picture'] = $picture;
        $validationRule['picture'] = $picture;
      }
      if ($request->esignature) {

        $image = $request->esignature;
        $image = Str::after($image, 'base64,');
        $image = str_replace(' ', '+', $image);
        $imageName = (string) Str::uuid() . '.' . 'jpg';
        File::put(storage_path() . "/app/public/producteurs/pieces/" . $imageName, base64_decode($image));
        $esignature = "public/producteurs/pieces/$imageName";

        $validationRule['esignature'] = $esignature;
      }
      $producteur->proprietaires = $request->proprietaires;
      $producteur->statutMatrimonial = $request->statutMatrimonial;
      $producteur->variete = $request->variete;
      $producteur->autreVariete = $request->autreVariete;
      $producteur->programme_id = $request->programme_id;
      $producteur->localite_id = $request->localite_id;
      $producteur->habitationProducteur = $request->habitationProducteur;
      $producteur->autreMembre = $request->autreMembre;
      $producteur->autrePhone = $request->autrePhone;
      $producteur->numPiece = $request->numPiece;
      $producteur->num_ccc = $request->num_ccc;
      $producteur->carteCMU = $request->carteCMU;
      $producteur->typeCarteSecuriteSociale = $request->typeCarteSecuriteSociale;
      $producteur->numSecuriteSociale = $request->numSecuriteSociale;
      $producteur->numCMU = $request->numCMU;
      $producteur->anneeDemarrage = $request->anneeDemarrage;
      $producteur->anneeFin = $request->anneeFin;
      $producteur->autreCertificats = $request->autreCertificats;
      $producteur->autreVariete = $request->autreVariete;
      $producteur->consentement  = $request->consentement;
      $producteur->statut  = $request->statut;
      $producteur->certificat     = $request->certificat;
      $producteur->nom = $request->nom;
      $producteur->prenoms    = $request->prenoms;
      $producteur->sexe    = $request->sexe;
      $producteur->nationalite    = $request->nationalite;
      $producteur->dateNaiss    = $request->dateNaiss;
      $producteur->phone1    = $request->phone1;
      $producteur->phone2    = $request->phone2;
      $producteur->niveau_etude    = $request->niveau_etude;
      $producteur->type_piece    = $request->type_piece;
      $producteur->numPiece    = $request->numPiece;
      $producteur->certificats   = $request->certificats;
      if (auth()->check()) {
        // Utilisateur authentifié, attribuer l'ID de l'utilisateur
        $producteur->userid = auth()->user()->id;
      }
      $producteur->codeProd = $request->codeProd;
      $producteur->plantePartage = $request->plantePartage;
      $producteur->update($request->all());

      $message = "Le producteur a été mis à jour avec succès";
    } 
    else {
      $validationRule = [
        'programme_id' => 'required|exists:programmes,id',
        'proprietaires' => 'required',
        'certificats' => 'required',
        'variete' => 'required',
        'habitationProducteur' => 'required',
        'statut' => 'required',
        'statutMatrimonial' => 'required',
        'localite_id'    => 'required|exists:localites,id',
        'nom' => 'required|max:255',
        'prenoms'  => 'required|max:255',
        'sexe'  => 'required|max:255',
        'nationalite'  => 'required|max:255',
        'dateNaiss'  => 'required|max:255',
        'phone1'  => 'required|max:255',
        'niveau_etude'  => 'required|max:255',
        'type_piece'  => 'required|max:255',
        'numPiece'  => 'required|max:255',
        'num_ccc' => ['unique:producteurs,num_ccc'],
        'anneeDemarrage' => 'required_if:proprietaires,==,Garantie',
        'anneeFin' => 'required_if:proprietaires,==,Garantie',
        'plantePartage' => 'required_if:proprietaires,==,Planté-partager',
        'typeCarteSecuriteSociale' => 'required',
        'autreCertificats' => 'required_if:certificats,==,Autre',
        'autreVariete' => 'required_if:variete,==,Autre',
        'codeProd' => 'required_if:statut,==,Certifie',
        'certificat' => 'required_if:statut,==,Certifie',
        'phone2' => 'required_if:autreMembre,==,oui',
        'autrePhone' => 'required_if:autreMembre,==,oui',
        'numCMU' => 'required_if:carteCMU,==,oui',
      ];
      $message = [
        'programme_id.required' => 'Le programme est obligatoire',
        'proprietaires.required' => 'Le type de propriétaire est obligatoire',
        'certificats.required' => 'Le type de certificat est obligatoire',
        'variete.required' => 'Le type de variété est obligatoire',
        'habitationProducteur.required' => 'Le type d\'habitation est obligatoire',
        'statut.required' => 'Le statut est obligatoire',
        'statutMatrimonial.required' => 'Le statut matrimonial est obligatoire',
        'localite_id.required' => 'La localité est obligatoire',
        'nom.required' => 'Le nom est obligatoire',
        'prenoms.required' => 'Le prénom est obligatoire',
        'sexe.required' => 'Le sexe est obligatoire',
        'nationalite.required' => 'La nationalité est obligatoire',
        'dateNaiss.required' => 'La date de naissance est obligatoire',
        'phone1.required' => 'Le numéro de téléphone est obligatoire',
        'niveau_etude.required' => 'Le niveau d\'étude est obligatoire',
        'type_piece.required' => 'Le type de pièce est obligatoire',
        'numPiece.required' => 'Le numéro de pièce est obligatoire',
        'num_ccc.unique' => 'Le numéro de CCC existe déjà',
        'anneeDemarrage.required_if' => 'L\'année de démarrage est obligatoire',
        'anneeFin.required_if' => 'L\'année de fin est obligatoire',
        'plantePartage.required_if' => 'Le type de plante est obligatoire',
        'typeCarteSecuriteSociale.required' => 'Le type de carte de sécurité sociale est obligatoire',
        'autreCertificats.required_if' => 'Le type de certificat est obligatoire',
        'autreVariete.required_if' => 'Le type de variété est obligatoire',
        'codeProdapp.required_if' => 'Le code Prodapp est obligatoire',
        'certificat.required_if' => 'Le certificat est obligatoire',
        'phone2.required_if' => 'Le numéro de téléphone est obligatoire',
        'autrePhone.required_if' => 'Le champ membre de famille est obligatoire',
      ];
      $request->validate($validationRule, $message);
      $producteur = new Producteur();
      $producteur->proprietaires = $request->proprietaires;
      $producteur->statutMatrimonial = $request->statutMatrimonial;
      $producteur->variete = $request->variete;
      $producteur->autreVariete = $request->autreVariete;
      $producteur->programme_id = $request->programme_id;
      $producteur->localite_id = $request->localite_id;
      $producteur->habitationProducteur = $request->habitationProducteur;
      $producteur->autreMembre = $request->autreMembre;
      $producteur->autrePhone = $request->autrePhone;
      $producteur->numPiece = $request->numPiece;
      $producteur->num_ccc = $request->num_ccc;
      $producteur->carteCMU = $request->carteCMU;
      $producteur->typeCarteSecuriteSociale = $request->typeCarteSecuriteSociale;
      $producteur->numSecuriteSociale = $request->numSecuriteSociale;
      $producteur->numCMU = $request->numCMU;
      $producteur->anneeDemarrage = $request->anneeDemarrage;
      $producteur->anneeFin = $request->anneeFin;
      $producteur->certificats   = $request->certificats;
      $producteur->autreCertificats = $request->autreCertificats;
      $producteur->autreVariete = $request->autreVariete;
      $producteur->consentement  = $request->consentement;
      $producteur->statut  = $request->statut;
      $producteur->certificat     = $request->certificat;
      $producteur->nom = $request->nom;
      $producteur->prenoms    = $request->prenoms;
      $producteur->sexe    = $request->sexe;
      $producteur->nationalite    = $request->nationalite;
      $producteur->dateNaiss    = $request->dateNaiss;
      $producteur->phone1    = $request->phone1;
      $producteur->phone2    = $request->phone2;
      $producteur->niveau_etude    = $request->niveau_etude;
      $producteur->type_piece    = $request->type_piece;
      $producteur->numPiece    = $request->numPiece;
      if (auth()->check()) {
        // Utilisateur authentifié, attribuer l'ID de l'utilisateur
        $producteur->userid = auth()->user()->id;
      }
      $producteur->codeProd = $request->codeProd;
      $producteur->plantePartage = $request->plantePartage;
      if (!file_exists(storage_path() . "/app/public/producteurs/pieces")) {
        File::makeDirectory(storage_path() . "/app/public/producteurs/pieces", 0777, true);
      }
      if ($request->hasFile('picture')) {
        try {
          $producteur->picture = $request->file('picture')->store('public/producteurs/photos');
        } catch (\Exception $exp) {
          $notify[] = ['error', 'Impossible de télécharger votre image'];
          return back()->withNotify($notify);
        }
      }
      $producteur->save();
      $message = "Le producteur a été créé avec succès";
    }
    return response()->json($producteur, 201);
  }

  public function apiinfosproducteur(StoreInfoRequest $request)
  {
    DB::beginTransaction();
    try {
      $request->validated();
      $producteur = Producteur::where('id', $request->producteur_id)->first();
      if ($producteur->status == Status::NO) {
        $notify = 'Ce producteur est désactivé';
        return response()->json($notify, 201);
      }
      if ($request->id) {
        $infoproducteur = Producteur_info::findOrFail($request->id);
        $message = "L'info du producteur a été mise à jour avec succès";
      } else {
        $infoproducteur = new Producteur_info();

        $hasInfoProd = Producteur_info::where('producteur_id', $request->producteur_id)->exists();

        if ($hasInfoProd) {
          $notify = "L'info existe déjà pour ce producteur. Veuillez apporter des mises à jour.";
          return response()->json($notify, 201);
        }
      }
      $infoproducteur->producteur_id = $request->producteur_id;
      $infoproducteur->foretsjachere  = $request->foretsjachere;
      $infoproducteur->superficie  = $request->superficie;
      $infoproducteur->autresCultures = $request->autresCultures;
      $infoproducteur->autreActivite = $request->autreActivite;
      $infoproducteur->travailleurs = $request->travailleurs;
      $infoproducteur->travailleurspermanents = $request->travailleurspermanents;
      $infoproducteur->travailleurstemporaires = $request->travailleurstemporaires;
      $infoproducteur->mobileMoney = $request->mobileMoney;
      $infoproducteur->compteBanque    = $request->compteBanque;
      $infoproducteur->nomBanque    = $request->nomBanque;
      $infoproducteur->mainOeuvreFamilial = $request->mainOeuvreFamilial;
      $infoproducteur->travailleurFamilial    = $request->travailleurFamilial;
      if (auth()->check()) {
        // Utilisateur authentifié, attribuer l'ID de l'utilisateur
        $infoproducteur->userid = auth()->user()->id;
      }
      $infoproducteur->save();
      if ($infoproducteur != null) {
        $id = $infoproducteur->id;
        if (($request->typeculture != null)) {

          $verification   = Producteur_infos_typeculture::where('producteur_info_id', $id)->get();
          if ($verification->count()) {
            DB::table('producteur_infos_typecultures')->where('producteur_info_id', $id)->delete();
          }
          $i = 0;

          foreach ($request->typeculture as $data) {
            if ($data != null) {
              DB::table('producteur_infos_typecultures')->insert(['producteur_info_id' => $id, 'typeculture' => $data, 'superficieculture' => $request->superficieculture[$i]]);
            }
            $i++;
          }
        }
        if ($request->typeactivite != null) {
          $verification   = Producteur_infos_autresactivite::where('producteur_info_id', $id)->get();
          if ($verification->count()) {
            DB::table('producteur_infos_autresactivites')->where('producteur_info_id', $id)->delete();
          }
          $i = 0;
          foreach ($request->typeactivite as $data) {
            if ($data != null) {
              DB::table('producteur_infos_autresactivites')->insert(['producteur_info_id' => $id, 'typeactivite' => $data]);
            }
            $i++;
          }
        }
        if ($request->operateurMM != null && $request->numeros != null) {
          $verification   = Producteur_infos_mobile::where('producteur_info_id', $id)->get();
          if ($verification->count()) {
            DB::table('producteur_infos_mobiles')->where('producteur_info_id', $id)->delete();
          }
          $i = 0;
          foreach ($request->operateurMM as $data) {
            if ($data != null) {
              DB::table('producteur_infos_mobiles')->insert(['producteur_info_id' => $id, 'operateur' => $data, 'numero' => $request->numeros[$i]]);
            }
            $i++;
          }
        }
      }
    } catch (ValidationException $e) {
      DB::rollBack();
    }

    DB::commit();
    return response()->json($infoproducteur, 201);
  }

  public function getproducteurUpdate(Request $request)
  {


    $input = $request->all();
    if ($request->userid) {
      $userid = $input['userid'];


      $producteur = DB::select(DB::raw("SELECT * FROM producteurs WHERE (localite_id is null or  
    nationalite_id is null or  
    type_piece_id is null or 
    codeProd is null or  
    picture is null or 
    nom is null or 
    prenoms is null or 
    sexe is null or 
    dateNaiss is null or 
    phone1 is null or 
    numPiece is null or  
    niveaux_id is null or  
    picture is null or 
    copiecarterecto is null or 
    copiecarteverso is null or 
    consentement is null or 
    statut is null or 
    certificat is null or 
    esignature is null 
  )
    AND deleted_at IS NULL
    "));

      if (isset($input['id'])) {
        if ($request->picture) {
          $image = $request->picture;
          $image = Str::after($image, 'base64,');
          $image = str_replace(' ', '+', $image);
          $imageName = (string) Str::uuid() . '.' . 'jpg';
          File::put(storage_path() . "/app/public/producteurs/pieces/" . $imageName, base64_decode($image));
          $picture = "public/producteurs/pieces/$imageName";
          $input['picture'] = $picture;
        }
        if ($request->copiecarterecto) {
          $image = $request->copiecarterecto;
          $image = Str::after($image, 'base64,');
          $image = str_replace(' ', '+', $image);
          $imageName = (string) Str::uuid() . '.' . 'jpg';
          File::put(storage_path() . "/app/public/producteurs/pieces/" . $imageName, base64_decode($image));
          $copiecarterecto = "public/producteurs/pieces/$imageName";
          $input['copiecarterecto'] = $copiecarterecto;
        }
        if ($request->copiecarteverso) {

          $image = $request->copiecarteverso;
          $image = Str::after($image, 'base64,');
          $image = str_replace(' ', '+', $image);
          $imageName = (string) Str::uuid() . '.' . 'jpg';
          File::put(storage_path() . "/app/public/producteurs/pieces/" . $imageName, base64_decode($image));
          $copiecarteverso = "public/producteurs/pieces/$imageName";
          $input['copiecarteverso'] = $copiecarteverso;
        }
        if ($request->esignature) {

          $image = $request->esignature;
          $image = Str::after($image, 'base64,');
          $image = str_replace(' ', '+', $image);
          $imageName = (string) Str::uuid() . '.' . 'jpg';
          File::put(storage_path() . "/app/public/producteurs/pieces/" . $imageName, base64_decode($image));
          $esignature = "public/producteurs/pieces/$imageName";

          $input['esignature'] = $esignature;
        }

        $producteur = Producteur::find($input['id']);
        $producteur->update($input);

        $producteur = Producteur::find($input['id']);
      }
    } else {
      $producteur = array();
    }

    return response()->json($producteur, 201);
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {

    //
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

    dd(Producteur::find($id));
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
