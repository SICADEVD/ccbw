<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\Localite;
use App\Constants\Status;
use App\Models\Producteur;
use Illuminate\Http\Request;
use App\Models\Agroevaluation;
use App\Models\Agroespecesarbre;
use App\Models\AgroevaluationEspece;
use App\Models\User;

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

        $agroevaluation->producteur_id  = $request->producteur;
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

    public function getproducteurs(Request $request){
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
}
