<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Localite;
use App\Models\Parcelle;
use App\Constants\Status;
use App\Models\Producteur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\agroespeceabre_parcelle;
use App\Models\DebugMobile;
use App\Models\Parcelle_type_protection;

class ApiparcelleController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $userid = $request->userid;
    $staff = User::where('id', $userid)->first();

    $parcelles = Parcelle::joinRelationship('producteur.localite.section.cooperative')->where([['cooperative_id', $staff->cooperative_id], ['producteurs.status', 1]])
      ->where(function ($query) {
        $query->where('typedeclaration', '!=', 'GPS')
          ->orWhereNull('anneeCreation')
          ->orWhereNull('typedeclaration')
          ->orWhereNull('parcelles.latitude')
          ->orWhereNull('parcelles.longitude')
          ->orWhereNull('codeParc');
      })
      ->select('parcelles.*', 'producteurs.nom', 'producteurs.prenoms', 'producteurs.codeProd', 'localites.nom as localite', 'sections.libelle as section', 'cooperatives.name as cooperative')
      ->get();

    return response()->json($parcelles, 200);
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
    // $debugp = new DebugMobile();
    // $debugp->content = json_encode($request->all());
    // $debugp->save();

    if ($request->id) {
      $parcelle = Parcelle::find($request->id);
      $parcelle->codeParc = $parcelle->codeParc;
      if ($parcelle->codeParc == '') {
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
      $produc = Producteur::select('codeProd')->find($request->producteur_id);
      if ($produc != null) {
        $codeProd = $produc->codeProd;
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
      $data = Parcelle::where('producteur_id', $idProd)->get();
      if ($data != '') {
        $nombreParcelles = $data->count();
        $numero = $nombreParcelles + 1;
        $codeProd = Str::beforeLast($codeProd, '-');
        $codeParc = $codeProd . '-P' . $numero;
        do {
          $verif = Parcelle::select('codeParc')->where('codeParc', $codeParc)->orderby('id', 'desc')->first();
          if ($verif == null) {
            $action = 'non';
          } else {
            $action = 'oui';
            $numero++;
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

  private function calculateCentroid($coordinates)
  {
      /*
      Calcule le centroïde d'un polygone à partir de ses coordonnées.
  
      Args:
          $coordinates (str): Les coordonnées du polygone.
  
      Returns:
          str: Les coordonnées du centroïde.
      */
      // Convertir les coordonnées en une liste de tuples
      $coords = array_map(function ($coord) {
          return array_map('floatval', explode(',', $coord));
      }, explode(' ', $coordinates));

      // Calculer la somme des coordonnées
      $sum_x = array_sum(array_column($coords, 0));
      $sum_y = array_sum(array_column($coords, 1));

      // Calculer le centroïde
      $centroid_x = $sum_x / count($coords);
      $centroid_y = $sum_y / count($coords);

      // Retourner les coordonnées du centroïde

      return array('lat' => number_format($centroid_y, 6), 'lng' => number_format($centroid_x, 6));
  }

  private function calculatePolygonArea($coordinates)
  {
      /*
      Calcule l'aire d'un polygone à partir de ses coordonnées.
  
      Args:
          $coordinates (str): Les coordonnées du polygone.
  
      Returns:
          float: L'aire du polygone.
      */
      // Convertir les coordonnées en une liste de tuples
      $coords = array_map(function ($coord) {
          return array_map('floatval', explode(',', $coord));
      }, explode(' ', $coordinates));

      // Calculer l'aire
      $area = 0.0;
      for ($i = 0; $i < count($coords); $i++) {
          $j = ($i + 1) % count($coords);
          $area += $coords[$i][0] * $coords[$j][1] - $coords[$j][0] * $coords[$i][1];
      }
      $area /= 2.0;

      // Retourner l'aire
      return abs($area) * 0.0001;
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
