<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Campagne;
use App\Constants\Status;
use App\Models\Livraison;
use App\Models\Programme;
use App\Models\Producteur;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LivraisonInfo;
use App\Models\LivraisonPrime;
use App\Models\CampagnePeriode;
use App\Models\LivraisonScelle;
use App\Models\LivraisonPayment;
use App\Models\LivraisonProduct;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\DB;
use App\Models\StockMagasinSection;
use Illuminate\Support\Facades\File;
use App\Models\Livraisons_temporaire;

class ApilivraisonController extends Controller
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

        $request->validate([
            'sender_staff' => 'required|exists:users,id',
            'magasin_section' =>  'required|exists:magasin_sections,id',
            'items'            => 'required|array',
            'items.*.type'     => 'required',
            'items.*.producteur'     => 'required|integer',
            'items.*.parcelle'     => 'required|integer',
            'items.*.quantity' => 'required|numeric|gt:0',
            'items.*.amount'   => 'required|numeric|gt:0',
            'estimate_date'    => 'required|date|date_format:Y-m-d',
        ]);
        $sender                      = User::where('id', $request->userid)->first();
        $livraison                     = new LivraisonInfo();
        $livraison->invoice_id         = getTrx();
        $livraison->code               = getTrx();
        $livraison->sender_cooperative_id   = $sender->cooperative_id;
        $livraison->sender_staff_id    = $request->sender_staff;
        $livraison->sender_name        = $request->sender_name;
        $livraison->sender_email       = $request->sender_email;
        $livraison->sender_phone       = $request->sender_phone;
        $livraison->sender_address     = $request->sender_address;
        $livraison->receiver_name      = $request->receiver_name;
        $livraison->receiver_email     = $request->receiver_email;
        $livraison->receiver_phone     = $request->receiver_phone;
        $livraison->receiver_address   = $request->receiver_address;
        $livraison->receiver_cooperative_id = $sender->cooperative_id;
        $livraison->receiver_magasin_section_id = $request->magasin_section;
        $livraison->estimate_date      = $request->estimate_date;

        $livraison->quantity      = array_sum(Arr::pluck($request->items, 'quantity'));
        $livraison->save();

        $subTotal = $stock = 0;
        $campagne = Campagne::active()->first();
        $periode = CampagnePeriode::where([['campagne_id', $campagne->id], ['periode_debut', '<=', gmdate('Y-m-d')], ['periode_fin', '>=', gmdate('Y-m-d')]])->latest()->first();
        $data = $data2 = $data3 = [];
        foreach ($request->items as $item) {
            // $livraisonType = Type::where('id', $item['type'])->first();
            // if (!$livraisonType) {
            //     continue;
            // }
            $price = $periode->prix_champ * $item['quantity'];
            $subTotal += $price;

            $data[] = [
                'livraison_info_id' => $livraison->id,
                'parcelle_id' => $item['parcelle'],
                'campagne_id' => $campagne->id,
                'campagne_periode_id' => $periode->id,
                'qty'             => $item['quantity'],
                'type_produit'     => $item['type'],
                'fee'             => $price,
                'type_price'      => $periode->prix_champ,
                'created_at'      => now(),
            ];
            $prod = StockMagasinSection::where([['campagne_id', $campagne->id], ['magasin_section_id', $request->magasin_section], ['producteur_id', $item['producteur']], ['type_produit', $item['type']]])->first();
            if ($prod == null) {
                $prod = new StockMagasinSection();
            }

            $prod->magasin_section_id = $request->magasin_section;
            $prod->producteur_id = $item['producteur'];
            $prod->campagne_id = $campagne->id;
            $prod->campagne_periode_id = $periode->id;
            $prod->stocks_entrant = $prod->stocks_entrant + $item['quantity'];
            $prod->type_produit = $item['type'];
            $prod->save();

            $product = Producteur::where('id', $item['producteur'])->first();
            if ($product != null) {
                $programme = $product->programme_id;
                $prime = Programme::where('id', $programme)->first();

                $price_prime = $prime->prime * $item['quantity'];
                $data3[] = [
                    'livraison_info_id' => $livraison->id,
                    'parcelle_id' => $item['parcelle'],
                    'campagne_id' => $campagne->id,
                    'campagne_periode_id' => $periode->id,
                    'quantite'             => $item['quantity'],
                    'montant'             => $price_prime,
                    'prime_campagne'      => $prime->prime,
                    'created_at'      => now(),
                ];
            }
        }

        LivraisonProduct::insert($data);
        LivraisonPrime::insert($data3);

        $totalAmount                     = $subTotal;

        $livraisonPayment                  = new LivraisonPayment();
        $livraisonPayment->livraison_info_id = $livraison->id;
        $livraisonPayment->campagne_id  = $campagne->id;
        $livraisonPayment->amount          = $subTotal;
        $livraisonPayment->final_amount    = $totalAmount;
        $livraisonPayment->save();

        if ($livraisonPayment->status == Status::PAYE) {
            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $sender->id;
            $adminNotification->title     = 'Livraison Payment ' . $sender->username;
            $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
            $adminNotification->save();
        }

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $sender->id;
        $adminNotification->title     = 'New livraison created to' . $sender->username;
        $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
        $adminNotification->save();


        return response()->json($livraison, 201);
    }

    public function getMagasinsection(Request $request)
    {
        // $input = $request->all();  
        // $userid = $input['userid'];
        $magasins = DB::table('magasin_sections')->get();
        return response()->json($magasins, 201);
    }

    public function getMagasincentraux(){
        $magasins = DB::table('magasin_centraux')->get();
        return response()->json($magasins, 201);
    }
    public function generecodeliv()
    {

        $data = Livraison::select('codeLiv')->orderby('id', 'desc')->limit(1)->get();

        if (count($data) > 0) {
            $code = $data[0]->codeLiv;
            $chaine_number = Str::afterLast($code, '-');
            if ($chaine_number < 10) {
                $zero = "00000";
            } else if ($chaine_number < 100) {
                $zero = "0000";
            } else if ($chaine_number < 1000) {
                $zero = "000";
            } else if ($chaine_number < 10000) {
                $zero = "00";
            } else if ($chaine_number < 100000) {
                $zero = "0";
            } else {
                $zero = "";
            }
        } else {
            $zero = "00000";
            $chaine_number = 0;
        }
        $sub = 'BL-';
        $lastCode = $chaine_number + 1;
        $codeLiv = $sub . $zero . $lastCode;

        return $codeLiv;
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
