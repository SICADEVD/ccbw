<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use App\Models\Section;
use App\Models\Campagne;
use App\Models\Parcelle;
use App\Models\Vehicule;
use App\Constants\Status;
use App\Models\Entreprise;
use App\Models\Producteur;
use App\Models\Cooperative;
use Illuminate\Support\Arr;
use App\Models\Transporteur;
use Illuminate\Http\Request;
use App\Models\Connaissement;
use App\Models\LivraisonInfo;
use App\Models\FormateurStaff;
use App\Models\LivraisonPrime;

use App\Models\MagasinCentral;
use App\Models\MagasinSection;
use App\Models\CampagnePeriode;
use App\Models\LivraisonScelle;
use App\Models\LivraisonPayment;
use App\Models\LivraisonProduct;
use App\Exports\ExportLivraisons;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\DB;
use App\Models\StockMagasinCentral;
use App\Models\StockMagasinSection;
use App\Http\Controllers\Controller;
use App\Models\LivraisonMagasinCentralProducteur;

class LivraisonCentraleController extends Controller
{

    public function index()
    {  
        $staff = auth()->user(); 
        $livraisonProd = LivraisonMagasinCentralProducteur::dateFilter()->joinRelationship('stockMagasinCentral')->with('stockMagasinCentral','campagne', 'producteur','campagnePeriode')
        ->where('cooperative_id', $staff->cooperative_id) 
        ->when(request()->code, function ($query, $code) {
            $query->where('numero_connaissement',$code); 
        })
        ->when(request()->magasin, function ($query, $magasin) {
            $query->where('stock_magasin_central_id',$magasin); 
        })
        ->when(request()->produit, function ($query, $produit) {
            $query->whereIn('livraison_magasin_central_producteurs.type_produit',json_decode($produit)); 
        })
        ->when(request()->producteur, function ($query, $producteur) {
            $query->where('producteur_id',$producteur); 
        })
        ->select('livraison_magasin_central_producteurs.*')
        ->orderBy('livraison_magasin_central_producteurs.id','desc')
        ->paginate(getPaginate());
 
        $total = $livraisonProd->sum('quantite');
        $pageTitle    = "Livraison des Magasins de Section ($total)";
        $magasins  = MagasinCentral::where('cooperative_id',$staff->cooperative_id)->get();
        $sections = Section::get();
        return view('manager.livraison-centrale.index', compact('pageTitle', 'livraisonProd','total','sections','magasins'));
    }

    public function stock()
    {  

        $staff = auth()->user(); 
        $stocks = StockMagasinCentral::dateFilter()->where([['cooperative_id',$staff->cooperative_id]]) 
        ->when(request()->magasin, function ($query, $magasin) {
            $query->where('magasin_section_id',$magasin); 
        })
        ->orderBy('stock_magasin_centraux.id','desc')
        ->with('cooperative','vehicule','transporteur','campagne','magasinCentral','magasinSection','campagnePeriode','vehicule.marque')
        ->paginate(getPaginate());
 
        $total = $stocks->sum('stocks_entrant');
        $pageTitle    = "Stock des Magasins de Section ($total)";
        $magasins  = MagasinCentral::where('cooperative_id',$staff->cooperative_id)->with('cooperative')->get();
        $sections = Section::get();
        return view('manager.livraison-centrale.stock', compact('pageTitle', 'stocks','total','sections','magasins'));
    }

    public function create()
    {
        $staff = auth()->user(); 
        $cooperatives = Cooperative::active()->orderBy('name')->get(); 
        $magCentraux = MagasinCentral::where([['cooperative_id',$staff->cooperative_id]])->with('user')->orderBy('nom')->get();
        $magSections = MagasinSection::joinRelationship('section')->where([['cooperative_id',$staff->cooperative_id]])->with('user')->orderBy('nom')->get();
    
       $transporteurs = Transporteur::where([['cooperative_id',$staff->cooperative_id]])->with('cooperative','entreprise')->get();
        $vehicules = Vehicule::with('marque')->get();
        $producteurs  = Producteur::joinRelationship('localite.section')->where('sections.cooperative_id',$staff->cooperative_id)->select('producteurs.*')->orderBy('producteurs.nom')->get();

        $campagne = Campagne::active()->first();
        $campagne = CampagnePeriode::where([['campagne_id',$campagne->id],['periode_debut','<=', gmdate('Y-m-d')],['periode_fin','>=', gmdate('Y-m-d')]])->latest()->first();
        $code = $this->generecodeConnais();
        $parcelles  = Parcelle::with('producteur')->get();
        $pageTitle = 'Connaissement vers Usine N° '.$code;
        $entreprises = Entreprise::all()->pluck('nom_entreprise', 'id');
        $formateurs = FormateurStaff::with('entreprise')->get();
        
        return view('manager.livraison-centrale.create', compact('pageTitle', 'cooperatives','magSections','magCentraux','producteurs','transporteurs','parcelles','campagne','vehicules','code','entreprises','formateurs'));
    }
    private function generecodeConnais()
    {

        $data = Connaissement::select('connaissement_code')->orderby('id','desc')->limit(1)->get();

        if(count($data)>0){
            $code = $data[0]->codeLiv;
        $chaine_number = Str::afterLast($code,'-');
        if($chaine_number<10){$zero="00000";}
        else if($chaine_number<100){$zero="0000";}
        else if($chaine_number<1000){$zero="000";}
        else if($chaine_number<10000){$zero="00";}
        else if($chaine_number<100000){$zero="0";}
        else{$zero="";}
        }else{
            $zero="00000";
            $chaine_number=0;
        }
        if(!$chaine_number) $chaine_number =0;
        $sub='NC-';
        $lastCode=$chaine_number+1;
        $codeLiv=$sub.$zero.$lastCode;

        return $codeLiv;
    }
    public function store(Request $request)
    {
        // dd(response()->json($request));
        
        $request->validate([
            'magasin_central' => 'required',
            'sender_magasin' =>  'required', 
            'sender_transporteur' =>  'required', 
            'sender_vehicule' =>  'required', 
            'producteur_id' => 'required|array',  
			'type' => 'required', 
            'estimate_date'    => 'required|date|date_format:Y-m-d', 
        ]);

        $manager                      = auth()->user();
        $livraison                     = new StockMagasinCentral(); 
        $campagne = Campagne::active()->first();
        $periode = CampagnePeriode::where([['campagne_id',$campagne->id],['periode_debut','<=', gmdate('Y-m-d')],['periode_fin','>=', gmdate('Y-m-d')]])->latest()->first();
        
        $livraison->cooperative_id   = $manager->cooperative_id;
        $livraison->campagne_id    = $campagne->id;
        $livraison->campagne_periode_id = $periode->id;
        $livraison->magasin_centraux_id = $request->magasin_central;
        $livraison->magasin_section_id = $request->sender_magasin;
        $livraison->numero_connaissement = $request->code;
        $livraison->type_produit = json_encode($request->type);
        $livraison->stocks_mag_entrant = $request->poidsnet;
        $livraison->stocks_mag_sacs_entrant = $request->nombresacs;
        $livraison->stocks_mag_sortant = 0;
        $livraison->stocks_mag_sacs_sortant   = 0;
        $livraison->transporteur_id = $request->sender_transporteur;
        $livraison->vehicule_id = $request->sender_vehicule;
        $livraison->date_livraison = $request->estimate_date;
       
        $livraison->save();
 
        $i=0;
        $data = [];
        $quantite = $request->quantite; 
        $typeproduit = $request->typeproduit; 
        $producteurs = $request->producteurs;
        
        foreach($producteurs as $item) { 
            
            if($quantite[$i]>0)
            {
            $data[] = [
                'stock_magasin_central_id' => $livraison->id,
                'producteur_id' => $item,
                'campagne_id' => $campagne->id,
                'campagne_periode_id' => $periode->id,
                'quantite' => $quantite[$i], 
                'type_produit' => $typeproduit[$i],
                'created_at'      => now(),
            ];
            $prod = StockMagasinSection::where([['campagne_id',$campagne->id],['magasin_section_id',$request->sender_magasin],['producteur_id',$item],['type_produit',$typeproduit[$i]]])->first();
         
            if($prod !=null){ 
                 
            $prod->stocks_entrant = $prod->stocks_entrant - $quantite[$i];
            $prod->stocks_sortant = $prod->stocks_sortant + $quantite[$i];
           
            $prod->save();
            }
        }
            $i++;
        }
		

        LivraisonMagasinCentralProducteur::insert($data); 

        $notify[] = ['success', 'Le connaissement vers le magasin central a été ajouté avec succès'];
        return to_route('manager.livraison.usine.stock')->withNotify($notify);
    }

    public function update(Request $request, $id)
    {

        $id = decrypt($id);
        
        
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

        $sender                      = auth()->user();
        $livraison                     = LivraisonInfo::findOrFail($id);
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
        $livraison->save();

        LivraisonProduct::where('livraison_info_id', $id)->delete();
        LivraisonScelle::where('livraison_info_id', $id)->delete();
        LivraisonPrime::where('livraison_info_id', $id)->delete();
        $subTotal = 0;
        $campagne = Campagne::active()->first();
        $data = $data2 = $data3 = [];
        foreach ($request->items as $item) {
             
            $price     = $campagne->prix_champ * $item['quantity'];
            $subTotal += $price;

            $data[] = [
                'livraison_info_id' => $livraison->id,
                'parcelle_id' => $item['parcelle'],
                'campagne_id' => $campagne->id,
                'qty'             => $item['quantity'],
                'type_produit'     => $item['type'],
                'fee'             => $price,
                'type_price'      => $campagne->prix_champ,
                'created_at'      => now(),
            ];
            // if(count($item['scelle'])){
            //     $scelles = implode(',', $item['scelle']);
            //     $scelles = explode(',', $scelles);
            //     foreach($scelles as $itemscelle){
            //         $data2[] = [
            //             'livraison_info_id' => $livraison->id,
            //             'parcelle_id' => $item['parcelle'],
            //             'campagne_id' => $campagne->id,
            //             'type_produit'     => $item['type'],
            //             'numero_scelle' => $itemscelle,
            //             'created_at'      => now(),
            //         ];
            //     }
            // }
            if($item['type']=='Certifie'){
                $price_prime = $campagne->prime * $item['quantity'];
                $data3[] = [
                    'livraison_info_id' => $livraison->id,
                    'parcelle_id' => $item['parcelle'],
                    'campagne_id' => $campagne->id,
                    'quantite'             => $item['quantity'], 
                    'montant'             => $price_prime,
                    'prime_campagne'      => $campagne->prime,
                    'created_at'      => now(),
                ];
            }
        }
        LivraisonProduct::insert($data);
        LivraisonScelle::insert($data2);
        LivraisonPrime::insert($data3);

        // $discount = $request->discount ?? 0;
        // $discountAmount = ($subTotal / 100) * $discount;
        $totalAmount = $subTotal;

        $user = auth()->user();
        if ($request->payment_status == Status::PAYE) {

            $livraisonPayment               = LivraisonPayment::where('livraison_info_id', $livraison->id)->first();
            $livraisonPayment->campagne_id  = $campagne->id;
            $livraisonPayment->amount       = $subTotal; 
            $livraisonPayment->final_amount = $totalAmount; 
            $livraisonPayment->status       = $request->payment_status;
            $livraisonPayment->save();

            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $sender->id;
            $adminNotification->title     = $livraison->code . ' Livraison Payment Updated  by ' . $user->username;
            $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
            $adminNotification->save();
        }

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $sender->id;
        $adminNotification->title     = $livraison->code . ' Livraison update by ' . $user->username;
        $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
        $adminNotification->save();

        $notify[] = ['success', 'Livraison updated successfully'];
        return to_route('manager.livraison.invoice', encrypt($livraison->id))->withNotify($notify);
    }

    public function getParcelle(){
        $input = request()->all();
        $id = $input['id'];
        $parcelles = Parcelle::where('producteur_id',$id)->get();
        if ($parcelles->count()) {
            $contents = '';

            foreach ($parcelles as $data) {
                $contents .= '<option value="' . $data->id . '" >Parcelle '. $data->codeParc . '</option>';
            }
        } else {
            $contents = null;
        }
 
        return $contents;
    }
    
    public function deliveryStore(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);
        $user = auth()->user();
        $livraison = StockMagasinCentral::where('numero_connaissement', $request->code)->where('status', Status::COURIER_DISPATCH)->firstOrFail();
 
        $livraison->status            = Status::COURIER_DELIVERYQUEUE;
        $livraison->save();
        $notify[] = ['success', 'Reception terminée'];
        return back()->withNotify($notify);
    }
    public function sentInQueue()
    {
        $pageTitle    = "Liste des livraisons en attente";
        $livraisonInfos = $this->livraisons('queue');
        return view('manager.livraison.index', compact('pageTitle', 'livraisonInfos'));
    }

    public function sentLivraison()
    {
        $manager      = auth()->user();
        $pageTitle    = "Liste des livraisons envoyées";
        $livraisonInfos = LivraisonInfo::where('sender_cooperative_id', $manager->cooperative_id)->where('status', '!=', Status::COURIER_QUEUE)->dateFilter()->searchable(['code'])->with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('manager.livraison.index', compact('pageTitle', 'livraisonInfos'));
    }

    public function deliveryInQueue()
    {
        $pageTitle    = "Liste des livraisons en attente de reception";
        $livraisonInfos = $this->livraisons('deliveryQueue');
        return view('manager.livraison.index', compact('pageTitle', 'livraisonInfos'));
    }

    public function dispatchLivraison()
    {
        $pageTitle    = "Liste des livraisons expédiées";
        $livraisonInfos = $this->livraisons('dispatched');
        return view('manager.livraison.index', compact('pageTitle', 'livraisonInfos'));
    }


    public function delivered()
    {
        $pageTitle    = "Liste des livraisons reçues";
        $livraisonInfos = $this->livraisons('delivered');
        return view('manager.livraison.index', compact('pageTitle', 'livraisonInfos'));
    }

    public function upcoming()
    {
        $pageTitle    = "Liste des livraisons encours";
        $livraisonInfos = $this->livraisons('upcoming');
        return view('manager.livraison.index', compact('pageTitle', 'livraisonInfos'));
    }

    protected function livraisons($scope = null)
    {
        $user     = auth()->user();
        $livraisons = LivraisonInfo::where(function ($query) use ($user) {
            $query->where('sender_cooperative_id', $user->cooperative_id)->orWhere('receiver_cooperative_id', $user->cooperative_id);
        });
        if ($scope) {
            $livraisons = $livraisons->$scope();
        }
        $livraisons = $livraisons->dateFilter()->searchable(['code'])->with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return $livraisons;
    }

    public function invoice($id)
    {
        $id                  = decrypt($id);
        $pageTitle           = "Facture";
        $livraisonInfo         = LivraisonInfo::with('payment')->findOrFail($id);
        return view('manager.livraison.invoice', compact('pageTitle', 'livraisonInfo'));
    }

    public function exportExcel()
    {
        return (new ExportLivraisons())->download('livraisons.xlsx');
    }

}
