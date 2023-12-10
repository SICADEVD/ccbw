<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use App\Models\Section;
use App\Models\Campagne;
use App\Models\Parcelle;
use App\Constants\Status;
use App\Models\Producteur;
use App\Models\Cooperative;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\LivraisonInfo;
use App\Models\LivraisonPrime;
use App\Models\MagasinSection;
use App\Models\MagasinCentral;
use App\Models\LivraisonScelle;
use App\Models\LivraisonPayment;
use App\Models\StockMagasinCentral;

use App\Models\LivraisonProduct;
use App\Exports\ExportLivraisons;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\DB;
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
        ->paginate(getPaginate());
 
        $total = $stocks->sum('stocks_entrant');
        $pageTitle    = "Stock des Magasins de Section ($total)";
        $magasins  = MagasinCentral::where('cooperative_id',$staff->cooperative_id)->get();
        $sections = Section::get();
        return view('manager.livraison-centrale.stock', compact('pageTitle', 'stocks','total','sections','magasins'));
    }

    public function create()
    {
        $pageTitle = 'Enregistrement de livraison';
        $staff = auth()->user();
        // $cooperatives = Cooperative::active()->where('id', '!=', auth()->user()->cooperative_id)->orderBy('name')->get();
        $cooperatives = Cooperative::active()->orderBy('name')->get(); 
        $magasins = MagasinSection::join('users','magasin_sections.staff_id','=','users.id')->where([['cooperative_id',$staff->cooperative_id],['magasin_sections.status',1]])->with('user')->orderBy('nom')->select('magasin_sections.*')->get();
    
        $staffs = User::whereHas('roles', function($q){ $q->whereIn('name', ['Delegue']); })
                ->where('cooperative_id', $staff->cooperative_id)
                ->select('users.*')
                ->get(); 

        $producteurs  = Producteur::joinRelationship('localite.section')->where('sections.cooperative_id',$staff->cooperative_id)->select('producteurs.*')->orderBy('producteurs.nom')->get();
        $campagne = Campagne::active()->first();
      
        $parcelles  = Parcelle::with('producteur')->get();
        
        return view('manager.livraison.create', compact('pageTitle', 'cooperatives','staffs','magasins','producteurs','parcelles','campagne'));
    }

    public function store(Request $request)
    {
        // dd(response()->json($request));
        
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
       
        $livraison->quantity      = array_sum(Arr::pluck($request->items,'quantity'));
        $livraison->save();

        $subTotal = 0;
        $campagne = Campagne::active()->first();
        $data = $data2 = $data3 = [];
        foreach ($request->items as $item) {
            // $livraisonType = Type::where('id', $item['type'])->first();
            // if (!$livraisonType) {
            //     continue;
            // }
            $price = $campagne->prix_champ * $item['quantity'];
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

        // $discount                        = $request->discount ?? 0;
        // $discountAmount                  = ($subTotal / 100) * $discount;
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

        $notify[] = ['success', 'Livraison added successfully'];
        return to_route('manager.livraison.invoice', encrypt($livraison->id))->withNotify($notify);
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
