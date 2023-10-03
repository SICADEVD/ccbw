<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Producteur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Producteur_info;
use App\Models\Infos_producteur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Producteur_infos_typeculture;
use App\Models\Producteur_infos_maladieenfant;
use App\Models\User;
use Exception;

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
    //
    $input = $request->all();
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
    ->select('producteurs.nom', 'producteurs.prenoms', 'localites.section_id as section_id', 'localites.id as localite_id', 'producteurs.id as producteur_id', 'producteurs.codeProd as codeProd', )
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

    //
    $input = $request->all();
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
      // 'num_ccc' => 'unique:producteurs,num_ccc',
    ];
    $request->validate($validationRule);
    // $coop = DB::table('localites as l')->join('cooperatives as c', 'l.cooperative_id', '=', 'c.id')->where('l.id', $input['localite_id'])->select('c.codeApp')->first();
    // if ($coop != null) {
    //   $input['codeProdapp'] = $this->generecodeProdApp($input['nom'], $input['prenoms'], $coop->codeApp);
    // } else {
    //   $input['codeProdapp'] = null;
    // }

    if (!file_exists(storage_path() . "/app/public/producteurs/pieces")) {
      File::makeDirectory(storage_path() . "/app/public/producteurs/pieces", 0777, true);
    }

    if ($request->picture) {
      $image = $request->picture;
      $image = Str::after($image, 'base64,');
      $image = str_replace(' ', '+', $image);
      $imageName = (string) Str::uuid() . '.' . 'jpg';
      File::put(storage_path() . "/app/public/producteurs/pieces/" . $imageName, base64_decode($image));
      $picture = "public/producteurs/pieces/$imageName";
      $input['picture'] = $picture;
    }
    // if ($request->copiecarterecto) {
    //   $image = $request->copiecarterecto;
    //   $image = Str::after($image, 'base64,');
    //   $image = str_replace(' ', '+', $image);
    //   $imageName = (string) Str::uuid() . '.' . 'jpg';

    //   File::put(storage_path() . "/app/public/producteurs/pieces/" . $imageName, base64_decode($image));
    //   $copiecarterecto = "public/producteurs/pieces/$imageName";
    //   $input['copiecarterecto'] = $copiecarterecto;
    // }
    // if ($request->copiecarteverso) {

    //   $image = $request->copiecarteverso;
    //   $image = Str::after($image, 'base64,');
    //   $image = str_replace(' ', '+', $image);
    //   $imageName = (string) Str::uuid() . '.' . 'jpg';
    //   File::put(storage_path() . "/app/public/producteurs/pieces/" . $imageName, base64_decode($image));
    //   $copiecarteverso = "public/producteurs/pieces/$imageName";
    //   $input['copiecarteverso'] = $copiecarteverso;
    // }
    if ($request->esignature) {

      $image = $request->esignature;
      $image = Str::after($image, 'base64,');
      $image = str_replace(' ', '+', $image);
      $imageName = (string) Str::uuid() . '.' . 'jpg';
      File::put(storage_path() . "/app/public/producteurs/pieces/" . $imageName, base64_decode($image));
      $esignature = "public/producteurs/pieces/$imageName";

      $input['esignature'] = $esignature;
    }

    $producteur = Producteur::create($input);

    return response()->json($producteur, 201);
  }

  public function apiinfosproducteur(Request $request)
  {

    $validationRule = [
      'producteur_id' => 'required|max:255',
      'autresCultures'  => 'required|max:255',
      'age18'  => 'required|max:255',
      'persEcole'  => 'required|max:255',
      'scolarisesExtrait'  => 'required|max:255',
      'travailleurs'  => 'required|max:255',
      'travailleurspermanents'  => 'required|max:255',
      'travailleurstemporaires'  => 'required|max:255',
      'personneBlessee'  => 'required|max:255',
      'typeDocuments'  => 'required|max:255',
      'recuAchat'  => 'required|max:255',
      'mobileMoney'  => 'required|max:255',
      'paiementMM'  => 'required|max:255',
      'compteBanque'  => 'required|max:255',
    ];


    $request->validate($validationRule);

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
    $infoproducteur->autresCultures     = $request->autresCultures;
    $infoproducteur->age18    = $request->age18;
    $infoproducteur->persEcole = $request->persEcole;
    $infoproducteur->scolarisesExtrait    = $request->scolarisesExtrait;
    $infoproducteur->travailleurs    = $request->travailleurs;
    $infoproducteur->travailleurspermanents    = $request->travailleurspermanents;
    $infoproducteur->travailleurstemporaires = $request->travailleurstemporaires;
    $infoproducteur->personneBlessee    = $request->personneBlessee;
    $infoproducteur->typeDocuments    = $request->typeDocuments;
    $infoproducteur->recuAchat    = $request->recuAchat;
    $infoproducteur->mobileMoney    = $request->mobileMoney;
    $infoproducteur->operateurMM    = $request->operateurMM;
    $infoproducteur->numeroCompteMM    = $request->numeroCompteMM;
    $infoproducteur->paiementMM    = $request->paiementMM;
    $infoproducteur->compteBanque    = $request->compteBanque;


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

      if (($request->maladiesenfants != null)) {

        $verification   = Producteur_infos_maladieenfant::where('producteur_info_id', $id)->get();
        if ($verification->count()) {
          DB::table('producteur_infos_maladieenfants')->where('producteur_info_id', $id)->delete();
        }
        $i = 0;

        foreach ($request->maladiesenfants as $data) {
          if ($data != null) {
            DB::table('producteur_infos_maladieenfants')->insert(['producteur_info_id' => $id, 'maladieenfant' => $data]);
          }
          $i++;
        }
      }
    }


    return response()->json($infoproducteur, 201);
  }

  private function generecodeProdApp($nom, $prenoms, $codeApp)
  {
    $action = 'non';

    $data = Producteur::select('codeProdapp')->join('localites as l', 'producteurs.localite_id', '=', 'l.id')->join('cooperatives as c', 'l.cooperative_id', '=', 'c.id')->where([
      ['codeProdapp', '!=', null], ['codeApp', $codeApp]
    ])->orderby('producteurs.id', 'desc')->first();

    if ($data != null) {

      $code = $data->codeProdapp;
      if ($code != null) {
        $chaine_number = Str::afterLast($code, '-');
      } else {
        $chaine_number = 0;
      }
    } else {
      $chaine_number = 0;
    }

    $lastCode = $chaine_number + 1;
    $codeP = $codeApp . '-' . gmdate('Y') . '-' . $lastCode;

    do {

      $verif = Producteur::select('codeProdapp')->where('codeProdapp', $codeP)->orderby('id', 'desc')->first();
      if ($verif == null) {
        $action = 'non';
      } else {
        $action = 'oui';
        $code = $codeP;
        $chaine_number = Str::afterLast($code, '-');
        $lastCode = $chaine_number + 1;
        $codeP = $codeApp . '-' . gmdate('Y') . '-' . $lastCode;
      }
    } while ($action != 'non');

    return $codeP;
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
