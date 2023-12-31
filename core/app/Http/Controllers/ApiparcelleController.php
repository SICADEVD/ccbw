<?php

namespace App\Http\Controllers;

use App\Models\Localite;
use App\Models\Parcelle;
use App\Constants\Status;
use App\Models\Producteur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Parcelle_type_protection;
use App\Models\agroespeceabre_parcelle;
use Illuminate\Support\Facades\DB;

class ApiparcelleController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $donnees = Parcelle::get();

    return response()->json($donnees, 201);
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

    $validationRule = [
      'producteur_id' => 'required',
      'anneeCreation' => 'required',
      'ageMoyenCacao' => 'required',
      'parcelleRegenerer' => 'required',
      'anneeRegenerer' => 'required_if:parcelleRegenerer,==,oui',
      'superficieConcerne' => 'required_if:parcelleRegenerer,==,oui',
      'typedeclaration' => 'required',
      'typeDoc' => 'required',
      'presenceCourDeau' => 'required',
      'courDeau' => 'required_if:presenceCourDeau,==,oui',
      'autreCourDeau' => 'required_if:courDeau,==,Autre',
      'existeMesureProtection' => 'required',
      'existePente' => 'required',
      'superficie' => 'required',
      'nbCacaoParHectare' => 'required|numeric',
      'erosion' => 'required',
      'items.*.arbre'     => 'required|integer',
      'items.*.nombre'     => 'required|integer',
      'longitude' => 'numeric|nullable',
      'latitude' => 'numeric|nullable',
    ];
    $messages = [
      'section.required' => 'Le champ section est obligatoire',
      'localite.required' => 'Le champ localité est obligatoire',
      'producteur_id.required' => 'Le champ producteur est obligatoire',
      'anneeCreation.required' => 'Le champ année de création est obligatoire',
      'ageMoyenCacao.required' => 'Le champ age moyen du cacao est obligatoire',
      'parcelleRegenerer.required' => 'Le champ parcelle à regénérer est obligatoire',
      'anneeRegenerer.required_if' => 'Le champ année de régénération est obligatoire',
      'superficieRegenerer.required_if' => 'Le champ superficie de régénération est obligatoire',
      'typeDoc.required' => 'Le champ type de document est obligatoire',
      'presenceCourDeau.required' => 'Le champ présence de cours d\'eau est obligatoire',
      'courDeau.required_if' => 'Le champ cours d\'eau est obligatoire',
      'existeMesureProtection.required' => 'Le champ existence de mesure de protection est obligatoire',
      'existePente.required' => 'Le champ existence de pente est obligatoire',
      'superficie.required' => 'Le champ superficie est obligatoire',
      'nbCacaoParHectare.required' => 'Le champ nombre de cacao par hectare est obligatoire',
      'superficieConcerne.required_if' => 'Le champ superficie concerné est obligatoire',
    ];
    $attributes = [
      'section' => 'section',
      'localite' => 'localité',
      'producteur_id' => 'producteur',
      'anneeCreation' => 'année de création',
      'ageMoyenCacao' => 'age moyen du cacao',
      'parcelleRegenerer' => 'parcelle regénéré',
      'anneeRegenerer' => 'L\'année de régénération',
      'superficieRegenerer' => 'superficie de régénérer',
      'typeDoc' => 'type de document',
      'presenceCourDeau' => 'présence de cours d\'eau',
      'courDeau' => 'cours d\'eau',
      'existeMesureProtection' => 'existence de mesure de protection',
      'existePente' => 'existence de pente',
      'superficie' => 'superficie',
      'nbCacaoParHectare' => 'nombre de cacao par hectare',
      'superficieConcerne' => 'superficie concerné',
    ];
    $request->validate($validationRule, $messages, $attributes);

    if ($request->id) {
      $parcelle = Parcelle::find($request->id);
      $codeParc = $parcelle->codeParc;
      if ($codeParc == '') {
        $produc = Producteur::select('codeProdapp')->find($request->producteur);
        if ($produc != null) {
          $codeProd = $produc->codeProdapp;
        } else {
          $codeProd = '';
        }
        $parcelle->codeParc  =  $this->generecodeparc($request->producteur, $codeProd);
      }
    } else {
      $parcelle = new Parcelle();
      $produc = Producteur::select('codeProdapp')->find($request->producteur_id);
      if ($produc != null) {
        $codeProd = $produc->codeProdapp;
      } else {
        $codeProd = '';
      }
      $parcelle->codeParc  =  $this->generecodeparc($request->producteur_id, $codeProd);
    }
    $parcelle->producteur_id  = $request->producteur_id;
    $parcelle->niveauPente  = $request->niveauPente;
    $parcelle->anneeCreation  = $request->anneeCreation;
    $parcelle->ageMoyenCacao  = $request->ageMoyenCacao;
    $parcelle->parcelleRegenerer  = $request->parcelleRegenerer;
    $parcelle->anneeRegenerer  = $request->anneeRegenerer;
    $parcelle->superficieConcerne  = $request->superficieConcerne;
    $parcelle->typeDoc  = $request->typeDoc;
    $parcelle->presenceCourDeau  = $request->presenceCourDeau;
    $parcelle->courDeau  = $request->courDeau;
    $parcelle->existeMesureProtection  = $request->existeMesureProtection;
    $parcelle->existePente  = $request->existePente;
    $parcelle->typedeclaration  = $request->typedeclaration;
    $parcelle->superficie  = $request->superficie;
    $parcelle->latitude  = $request->latitude;
    $parcelle->longitude  = $request->longitude;
    $parcelle->userid = $request->userid;
    $parcelle->nbCacaoParHectare  = $request->nbCacaoParHectare;
    $parcelle->erosion  = $request->erosion;
    $parcelle->autreCourDeau = $request->autreCourDeau;
    $parcelle->autreProtection = $request->autreProtection;

    if (isset($request->waypoints) && count($request->waypoints) > 0) {
      $parcelle->waypoints = implode(',', $request->waypoints);
    } else {
      $parcelle->waypoints = "";
    }
    if ($request->superficie) {
      $superficie = Str::before($request->superficie, ' ');
      if (Str::contains($superficie, ",")) {
        $superficie = Str::replaceFirst(',', '.', $superficie);
        if (Str::contains($superficie, ",")) {
          $superficie = Str::replaceFirst('m²', '', $superficie);
        }
      }

      $parcelle->superficie = $superficie;
    } else {
      $parcelle->superficie = 0;
    }
    $parcelle->save();
    if ($parcelle != null) {
      $id = $parcelle->id;
      $datas  = $data2 = [];
      if (($request->protection != null)) {
        Parcelle_type_protection::where('parcelle_id', $id)->delete();
        $i = 0;
        foreach ($request->protection as $protection) {
          if (!empty($protection)) {
            $datas[] = [
              'parcelle_id' => $id,
              'typeProtection' => $protection,
            ];
          }
          $i++;
        }
      }
      if (($request->items != null)) {
        agroespeceabre_parcelle::where('parcelle_id', $id)->delete();
        foreach ($request->items as $item) {

          $data2[] = [
            'parcelle_id' => $id,
            'nombre' => $item['nombre'],
            'agroespeceabre_id' => $item['arbre'],
          ];
        }
      }
    }
    Parcelle_type_protection::insert($datas);
    agroespeceabre_parcelle::insert($data2);
    // Parcelle::find($id)->agroespeceabre()->sync($data2);
    if ($parcelle == null) {
      return response()->json("La parcelle n'a pas été enregistré", 501);
    }

    return response()->json($parcelle, 201);
  }

  private function generecodeparc($idProd, $codeProd)
  {
    if ($codeProd) {
      $action = 'non';

      $data = Parcelle::select('codeParc')->where([
        ['producteur_id', $idProd],
        ['codeParc', '!=', null]
      ])->orderby('id', 'desc')->first();

      if ($data != '') {

        $code = $data->codeParc;

        if ($code != '') {
          $chaine_number = Str::afterLast($code, '-');
          $numero = Str::after($chaine_number, 'P');
          $numero = $numero + 1;
        } else {
          $numero = 1;
        }
        $codeParc = $codeProd . '-P' . $numero;

        do {

          $verif = Parcelle::select('codeParc')->where('codeParc', $codeParc)->orderby('id', 'desc')->first();
          if ($verif == null) {
            $action = 'non';
          } else {
            $action = 'oui';
            $code = $data->codeParc;

            if ($code != '') {
              $chaine_number = Str::afterLast($code, '-');
              $numero = Str::after($chaine_number, 'P');
              $numero = $numero + 1;
            } else {
              $numero = 1;
            }
            $codeParc = $codeProd . '-P' . $numero;
          }
        } while ($action != 'non');
      } else {
        $codeParc = $codeProd . '-P1';
      }
    } else {
      $codeParc = '';
    }

    return $codeParc;
  }

  public function getparcelleUpdate(Request $request)
  {


    $input = $request->all();
    if ($request->userid) {
      $userid = $input['userid'];
      $parcelle = DB::select(DB::raw("SELECT pa.*, p.nom, p.prenoms FROM parcelles as pa
      INNER JOIN producteurs as p ON pa.producteur_id=p.id
      WHERE pa.producteur_id ='' OR
        pa.codeParc  ='' OR
        pa.anneeCreation  ='' OR 
        pa.typedeclaration  ='' OR 
        pa.culture  ='' OR 
        pa.superficie  ='' OR 
        pa.latitude  ='' OR 
        pa.longitude  ='' OR 
        pa.waypoints  ='' OR
      typedeclaration !='GPS'
      AND pa.deleted_at IS NULL
      AND pa.userid='$userid'
      
      "));

      if (isset($input['id'])) {

        if (isset($input['waypoints']) && count($input['waypoints']) > 0) {
          $input['waypoints'] = serialize($input['waypoints']);
        } else {
          $input['waypoints'] = "";
        }
        $input['superficie'] = Str::before($input['superficie'], ' ');
        if (Str::contains($input['superficie'], ",")) {
          $input['superficie'] = Str::replaceFirst(',', '.', $input['superficie']);
          if (Str::contains($input['superficie'], ",")) {
            $input['superficie'] = Str::replaceFirst('m²', '', $input['superficie']);
          }
          // $input['superficie'] = $input['superficie']*0.0001;
        }
        $parcelle = Parcelle::find($input['id']);
        $parcelle->update($input);
        $parcelle = Parcelle::find($input['id']);
      }
    } else {
      $parcelle = array();
    }
    return response()->json($parcelle, 201);
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
