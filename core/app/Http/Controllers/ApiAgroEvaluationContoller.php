<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Campagne;
use App\Models\Localite;
use App\Constants\Status;
use App\Models\Producteur;
use Illuminate\Http\Request;
use App\Models\Agroevaluation;
use App\Models\Agrodistribution;
use App\Models\Agroespecesarbre;
use Illuminate\Support\Facades\DB;
use App\Models\AgroevaluationEspece;
use App\Models\AgrodistributionEspece;
use App\Models\AgroapprovisionnementSectionEspece;

class ApiAgroEvaluationContoller extends Controller
{
    public function store(Request $request)
    {
        $validationRule = [
            'localite'    => 'required|exists:localites,id',
            'producteur'    => 'required|exists:producteurs,id',
            'especesarbre'            => 'required|array',
            'quantite'            => 'required|array',
        ];

        $request->validate($validationRule);

        $localite = Localite::where('id', $request->localite)->first();

        if ($localite->status == Status::NO) {
            $notify[] = ['error', 'Cette localité est désactivée'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->id) {
            $agroevaluation = Agroevaluation::findOrFail($request->id);
            $message = "Le contenu a été mise à jour avec succès";
        } else {
            $agroevaluation = new Agroevaluation();
        }
        if ($agroevaluation->producteur_id != $request->producteur) {
            $hasEvalution = Agroevaluation::where('producteur_id', $request->producteur)->exists();
            if ($hasEvalution) {
                return response()->json("Ce producteur a déjà une évaluation de besoin enregistré", 501);
            }
        }

        $agroevaluation->producteur_id  = $request->producteur;
        $agroevaluation->userid  = $request->userid;
        $agroevaluation->quantite  = array_sum($request->quantite);
        $agroevaluation->save();
        $k = 0;
        $i = 0;
        $datas = [];
        if ($agroevaluation != null) {
            $id = $agroevaluation->id;
            if ($request->especesarbre) {
                AgroevaluationEspece::where('agroevaluation_id', $id)->delete();
                $quantite = $request->quantite;
                foreach ($request->especesarbre as $key => $data) {

                    $total = $quantite[$key];
                    if ($total != null) {
                        $datas[] = [
                            'agroevaluation_id' => $id,
                            'agroespecesarbre_id' => $data,
                            'total' => $total,
                        ];
                        $i++;
                    } else {
                        $k++;
                    }
                }
                AgroevaluationEspece::insert($datas);
            }
        }

        return response()->json($agroevaluation, 201);

        $message = "$i arbres à ombrage ont été exprimés comme besoin de cet Producteur.";

    }

    // public function getproducteursBesoin(Request $request){
        // $manager = User::where('id',$request->userid)->get()->first();
        // $listeprod = Agroevaluation::select('producteur_id')->get();
        // $dataProd = array();
        // if ($listeprod->count()) {
        //     foreach ($listeprod as $data) {
        //         $dataProd[] = $data->producteur_id;
        //     }
        // }
        // $producteurs = Producteur::joinRelationship('localite.section')->where('cooperative_id', $manager->cooperative_id)->whereNotIn('producteurs.id', $dataProd)->select('producteurs.id')->get();
        // return response()->json([
        //     'producteurs' => $producteurs,
        // ], 201);

    // }
    public function besoinproducteur( Request $request){
    //    $evaluations = DB::table('agroevaluation_especes')
    //    ->join('agroevaluations', 'agroevaluations.id', '=', 'agroevaluation_especes.agroevaluation_id')
    //    ->select('agroevaluation_especes.agroespecesarbre_id', 'agroevaluations.producteur_id','agroevaluation_especes.total')
    //    ->get();
    //    return response()->json([
    //        'evaluations' => $evaluations,
    //    ], 201);

    $manager = User::where('id',$request->userid)->get()->first();
    $listeprod = Agroevaluation::select('producteur_id')->get();
    $dataProd = array();
    if ($listeprod->count()) {
        foreach ($listeprod as $data) {
            $dataProd[] = $data->producteur_id;
        }
    }
    $producteurs = Producteur::joinRelationship('localite.section')->where('cooperative_id', $manager->cooperative_id)->whereNotIn('producteurs.id', $dataProd)->select('producteurs.id')->get();
    return response()->json([
        'producteurs' => $producteurs,
    ], 201);
    

    }
    public function store_distribution(Request $request)
    {
        $validationRule = [
            'quantite' => 'required|array',
        ];

        
        $request->validate($validationRule);


        if ($request->id) {
            $distribution = Agrodistribution::findOrFail($request->id);
            $message = "La distribution a été mise à jour avec succès";
        } else {
            $distribution = new Agrodistribution();
        }
        $manager   = User::where('id', $request->userid)->first();
        $campagne = Campagne::active()->first();

        $datas = [];
        $k = 0;
        $i = 0;
        $nb = 0;
        
        if ($request->quantite) {
            $datas = [];
            foreach ($request->quantite as $producteurid => $agroespeces) {

                $existe = Agrodistribution::where('producteur_id', $producteurid)->first();

                if ($existe != null) {
                    $distributionID = $existe->id;
                    $datas = [
                        'id' => $distributionID,
                        'quantite' => $request->qtelivre,
                    ];
                } else {

                    $distribution = new Agrodistribution();
                    $distribution->cooperative_id = $manager->cooperative_id;
                    $distribution->producteur_id = $producteurid;
                    $distribution->quantite = $request->qtelivre;
                    $distribution->save();
                    $distributionID = $distribution->id;
                    $nb++;
                    $datas = [
                        'id' => $distributionID,
                        'quantite' => $request->qtelivre,
                    ];
                }
                $agroespeces = array_filter($agroespeces);
                foreach ($agroespeces as $agroespecesarbresid => $total) {

                    $find = AgrodistributionEspece::where([
                        ['agrodistribution_id', $distributionID],
                        ['agroespecesarbre_id', $agroespecesarbresid]
                    ])->first();
                    if ($find == null) {

                        if ($total != null) {
                            AgrodistributionEspece::insert([
                                'agrodistribution_id' => $distributionID,
                                'agroespecesarbre_id' => $agroespecesarbresid,
                                'total' => $total,
                                'created_at' => NOW()
                            ]);

                            $agroapprov = AgroapprovisionnementSectionEspece::joinRelationship('agroapprovisionnementSection')->where([['agroapprovisionnement_section_id', $request->agroapprovisionnementsection], ['agroapprovisionnement_section_especes.agroespecesarbre_id', $agroespecesarbresid]])->first();
                            if ($agroapprov != null) {
                                $agroapprov->total_restant = $agroapprov->total_restant + $total;
                                $agroapprov->save();
                            }
                            $i++;
                        } else {
                            $k++;
                        }
                    } else {
                        $k++;
                    }
                }
            }
            return response()->json($datas, 201);
        }
        return response()->json([], 409);
    }
    public function getApprovisionnementSection(){
        $approvisionnements = DB::table('agroapprovisionnement_sections')->get();
        return response()->json($approvisionnements, 201);

    }
}
