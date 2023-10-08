<?php

namespace App\Http\Controllers\Staff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Staff;
use App\Models\AdminNotification;
use App\Models\Cooperative;
use App\Models\Campagne;
use App\Models\LivraisonInfo;
use App\Models\LivraisonPayment;
use App\Models\LivraisonPrime;
use App\Models\LivraisonProduct;
use App\Models\LivraisonScelle;
use App\Models\Magasin_section;
use App\Models\Parcelle;
use App\Models\Producteur;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Dd;

class LivraisonController extends Controller
{
    public function livraisonInfo(){
        
    }
    public function create()
    {
        $pageTitle = 'Enregistrement de livraison';
        $staff = auth()->user();
        // $cooperatives = Cooperative::active()->where('id', '!=', auth()->user()->cooperative_id)->orderBy('name')->get();
        $cooperatives = Cooperative::active()->orderBy('name')->get(); 
        $magasins = Magasin_section::join('users','magasin_sections.staff_id','=','users.id')->where([['cooperative_id',$staff->cooperative_id],['magasin_sections.status',1]])->with('user')->orderBy('nom')->select('magasin_sections.*')->get();
        $staffs = User::active()->orderBy('lastname')->staff()->where('cooperative_id',$staff->cooperative_id)->with('cooperative')->get();
        $producteurs  = Producteur::join('localites','producteurs.localite_id','=','localites.id')->where('localites.cooperative_id',$staff->cooperative_id)->with('localite')->select('producteurs.*')->orderBy('producteurs.nom')->get();
        $campagne = Campagne::active()->first();
        $parcelles  = Parcelle::with('producteur')->get();
        
        return view('staff.livraison.index', compact('pageTitle', 'cooperatives','staffs','magasins','producteurs','parcelles','campagne'));
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
            'estimate_date'    => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'payment_status'   => 'required|integer|in:0,1',
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
        $livraison->save();

        $subTotal = 0;
        $campagne = Campagne::active()->first();
        $data = $data2 = $data3 = [];
        foreach ($request->items as $item) {
            // $livraisonType = Type::where('id', $item['type'])->first();
            // if (!$livraisonType) {
            //     continue;
            // }
            $price = $campagne->prix_achat * $item['quantity'];
            $subTotal += $price;
           
            $data[] = [
                'livraison_info_id' => $livraison->id,
                'parcelle_id' => $item['parcelle'],
                'campagne_id' => $campagne->id,
                'qty'             => $item['quantity'],
                'type_produit'     => $item['type'],
                'fee'             => $price,
                'type_price'      => $campagne->prix_achat,
                'created_at'      => now(),
            ];
            
            if(count($item['scelle'])){
                $scelles = implode(',', $item['scelle']);
                $scelles = explode(',', $scelles);
                foreach($scelles as $itemscelle){
                    $data2[] = [
                        'livraison_info_id' => $livraison->id,
                        'parcelle_id' => $item['parcelle'],
                        'campagne_id' => $campagne->id,
                        'type_produit'     => $item['type'],
                        'numero_scelle' => $itemscelle,
                        'created_at'      => now(),
                    ];
                }
            }

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

        $discount                        = $request->discount ?? 0;
        $discountAmount                  = ($subTotal / 100) * $discount;
        $totalAmount                     = $subTotal - $discountAmount;

        $livraisonPayment                  = new LivraisonPayment();
        $livraisonPayment->livraison_info_id = $livraison->id;
        $livraisonPayment->campagne_id  = $campagne->id;
        $livraisonPayment->amount          = $subTotal;
        $livraisonPayment->discount        = $discountAmount;
        $livraisonPayment->final_amount    = $totalAmount;
        $livraisonPayment->percentage      = $request->discount;
        $livraisonPayment->status          = $request->payment_status;
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
        return to_route('staff.livraison.invoice', encrypt($livraison->id))->withNotify($notify);
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
            'estimate_date'    => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'payment_status'   => 'required|integer|in:0,1',
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
             
            $price     = $campagne->prix_achat * $item['quantity'];
            $subTotal += $price;

            $data[] = [
                'livraison_info_id' => $livraison->id,
                'parcelle_id' => $item['parcelle'],
                'campagne_id' => $campagne->id,
                'qty'             => $item['quantity'],
                'type_produit'     => $item['type'],
                'fee'             => $price,
                'type_price'      => $campagne->prix_achat,
                'created_at'      => now(),
            ];
            if(count($item['scelle'])){
                $scelles = implode(',', $item['scelle']);
                $scelles = explode(',', $scelles);
                foreach($scelles as $itemscelle){
                    $data2[] = [
                        'livraison_info_id' => $livraison->id,
                        'parcelle_id' => $item['parcelle'],
                        'campagne_id' => $campagne->id,
                        'type_produit'     => $item['type'],
                        'numero_scelle' => $itemscelle,
                        'created_at'      => now(),
                    ];
                }
            }
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

        $discount = $request->discount ?? 0;
        $discountAmount = ($subTotal / 100) * $discount;
        $totalAmount = $subTotal - $discountAmount;

        $user = auth()->user();
        if ($request->payment_status == Status::PAYE) {

            $livraisonPayment               = LivraisonPayment::where('livraison_info_id', $livraison->id)->first();
            $livraisonPayment->campagne_id  = $campagne->id;
            $livraisonPayment->amount       = $subTotal;
            $livraisonPayment->discount     = $discountAmount;
            $livraisonPayment->final_amount = $totalAmount;
            $livraisonPayment->percentage   = $request->discount;
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
        return to_route('staff.livraison.invoice', encrypt($livraison->id))->withNotify($notify);
    }

    public function invoice($id)
    {
        $pageTitle = 'Facture';
        $livraisonInfo = LivraisonInfo::with('payment')->findOrFail(decrypt($id));
        return view('staff.invoice', compact('pageTitle', 'livraisonInfo'));
    }

    public function getParcelle(){
        $input = request()->all();
        $id = $input['id'];
        $parcelles = Parcelle::where('producteur_id',$id)->get();
        if ($parcelles->count()) {
            $contents = '<option disabled selected value="">Parcelle</option>';

            foreach ($parcelles as $data) {
                $contents .= '<option value="' . $data->id . '" >Parcelle '. $data->codeParc . '</option>';
            }
        } else {
            $contents = null;
        }
 
        return $contents;
    }

    public function edit($id)
    {
        $pageTitle   = 'Edit Livraison';
        $id          = decrypt($id);
        
        $user        = auth()->user();
        $cooperatives = Cooperative::active()->orderBy('name')->get(); 
        $magasins = Magasin_section::join('users','magasin_sections.staff_id','=','users.id')->where([['cooperative_id',$user->cooperative_id],['magasin_sections.status',1]])->with('user')->orderBy('nom')->select('magasin_sections.*')->get();
        $staffs = User::active()->orderBy('lastname')->where([['cooperative_id',$user->cooperative_id],['user_type','staff']])->with('cooperative')->get();
        $producteurs  = Producteur::join('localites','producteurs.localite_id','=','localites.id')->where('localites.cooperative_id',$user->cooperative_id)->with('localite')->select('producteurs.*')->orderBy('producteurs.nom')->get();
        $campagne = Campagne::active()->first();
        $parcelles  = Parcelle::with('producteur')->get();
        $scelles = LivraisonScelle::where('livraison_info_id', $id)->get();
  
        $livraisonInfo = LivraisonInfo::with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo','magasinSection')->where('sender_cooperative_id', $user->cooperative_id)->where('id', $id)->firstOrFail();
        
        if ($livraisonInfo->status != Status::COURIER_QUEUE) {
            $notify[] = ['error', "Vous ne pouvez mettre à jour que les envois en file d'attente livraison."];
            return back()->with($notify);
        }
        return view('staff.livraison.edit', compact('pageTitle', 'livraisonInfo', 'cooperatives','staffs','magasins','producteurs','parcelles','campagne','scelles'));
    }

    public function sentQueue()
    {
        $pageTitle    = "Livraison en attente";
        $user         = auth()->user();
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code', 'receiverCooperative:name'])->where('sender_cooperative_id', $user->cooperative_id)->where('status', Status::COURIER_QUEUE)->orderBy('id', 'DESC')
            ->with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo','magasinSection')->paginate(getPaginate());
        return view('staff.livraison.sentQueue', compact('pageTitle', 'livraisonLists'));
    }

    public function dispatchLivraison()
    {
        $pageTitle    = 'Livraison expédiée';
        $user         = auth()->user();
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code', 'receiverCooperative:name'])->where('sender_cooperative_id', $user->cooperative_id)->where('status', Status::COURIER_DISPATCH)->orderBy('id', 'DESC')
            ->with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo','magasinSection')->paginate(getPaginate());
        return view('staff.livraison.dispatch', compact('pageTitle', 'livraisonLists'));
    }

    public function upcoming()
    {
        $pageTitle    = 'Livraison encours';
        $user         = auth()->user();
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code'])->where('receiver_cooperative_id', $user->cooperative_id)->where('status', Status::COURIER_UPCOMING)->orderBy('id', 'DESC')
            ->with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo','magasinSection')->paginate(getPaginate());
           
        return view('staff.livraison.upcoming', compact('pageTitle', 'livraisonLists'));
    }

    public function dispatched($id)
    {
        $user                = auth()->user();
        $livraisonInfo         = LivraisonInfo::where('sender_cooperative_id', $user->cooperative_id)->findOrFail($id);
        $livraisonInfo->status = Status::COURIER_DISPATCH;
        $livraisonInfo->save();
        $notify[] = ['success', 'Livraison expédiée avec succès'];
        return back()->withNotify($notify);
    }

    public function deliveryLivraison($id)
    {
        $user                = auth()->user();
        $livraisonInfo         = LivraisonInfo::where('receiver_cooperative_id', $user->cooperative_id)->findOrFail($id);
        $livraisonInfo->status = Status::COURIER_DELIVERED;
        $livraisonInfo->save();

        $notify[] = ['success', 'Livraison livrée'];
        return back()->withNotify($notify);
    }

    public function deliveryQueue()
    {
        $pageTitle    = 'Livraison en attente de reception';
        $livraisonLists = $this->livraisons('deliveryQueue');
        return view('staff.livraison.deliveryQueue', compact('pageTitle', 'livraisonLists'));
    }

    public function delivered()
    {
        $pageTitle    = 'Livraison achevée';
        $livraisonLists = $this->livraisons('delivered');
        return view('staff.livraison.list', compact('pageTitle', 'livraisonLists'));
    }

    protected function livraisons($scope = null)
    {
        $user = auth()->user();
        $livraisons = LivraisonInfo::where(function ($query) use ($user) {
            $query->Where('receiver_cooperative_id', $user->cooperative_id);
        });
        if ($scope) {
            $livraisons = $livraisons->$scope();
        }
        $livraisons = $livraisons->dateFilter()->searchable(['code'])->with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return $livraisons;
    }

    public function receive($id)
    {
        $livraisonInfo         = LivraisonInfo::findOrFail($id);
        $livraisonInfo->status = Status::COURIER_DELIVERYQUEUE;
        $livraisonInfo->save();
        $notify[] = ['success', 'Livraison achevée avec succès.'];
        return back()->withNotify($notify);
    }

    public function livraisonList()
    {
        $user         = auth()->user();
        $pageTitle    = 'Liste des Livraisons';
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code', 'receiverCooperative:name'])->with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo','magasinSection')
            ->where('sender_cooperative_id', $user->cooperative_id)->orWhere('receiver_cooperative_id', $user->cooperative_id)->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('staff.livraison.list', compact('pageTitle', 'livraisonLists'));
    }

    public function details($id)
    {
        $pageTitle   = 'Livraison Details';
        $livraisonInfo = LivraisonInfo::with('campagne')->findOrFail(decrypt($id));
        return view('staff.livraison.details', compact('pageTitle', 'livraisonInfo'));
    }

    public function payment(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);
        $user = auth()->user();

        $livraison = LivraisonInfo::where('code', $request->code)
            ->where(function ($query) use ($user) {
                $query->where('sender_cooperative_id', $user->cooperative_id)->orWhere('receiver_cooperative_id', $user->cooperative_id);
            })
            ->whereIn('status', [Status::COURIER_QUEUE, Status::COURIER_DELIVERYQUEUE])
            ->firstOrFail();

        $livraisonPayment = LivraisonPayment::where('livraison_info_id', $livraison->id)
            ->where('status', Status::IMPAYE)
            ->firstOrFail();

        $livraisonPayment->receiver_id = $user->id;
        $livraisonPayment->cooperative_id   = $user->cooperative_id;
        $livraisonPayment->date        = Carbon::now();
        $livraisonPayment->status      = Status::PAYE;
        $livraisonPayment->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'Paiement Livraison ' . $user->username;
        $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
        $adminNotification->save();

        $notify[] = ['success', 'Paiement achevé'];
        return back()->withNotify($notify);
    }

    public function deliveryStore(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:livraison_infos,code',
        ]);
        $user = auth()->user();
        $livraison = LivraisonInfo::where('code', $request->code)->where('status', Status::COURIER_DELIVERYQUEUE)->firstOrFail();

        $livraison->receiver_staff_id = $user->id;
        $livraison->status            = Status::COURIER_DELIVERED;
        $livraison->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'Livraison Terminée ' . $user->username;
        $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
        $adminNotification->save();

        $notify[] = ['success', 'Livraison terminée'];
        return back()->withNotify($notify);
    }

    public function livraisonAllDispatch(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $ids  = $request->id;
        $id   = explode(',', $ids);
        $user = auth()->user();
        LivraisonInfo::whereIn('id', $id)->where('sender_cooperative_id', $user->cooperative_id)->update(['status' => Status::COURIER_DISPATCH]);
    }

    public function cash()
    {
        $user = auth()->user();
        $pageTitle = 'Revenus des Livraisons';
        $cooperativeIncomeLists = LivraisonPayment::where('receiver_id', $user->id)->select(DB::raw('*,SUM(final_amount) as totalAmount'))->groupBy('date')->paginate(getPaginate());
        return view('staff.livraison.cash', compact('pageTitle', 'cooperativeIncomeLists'));
    }

    public function sentLivraisonList()
    {
        $user = auth()->user();
        $pageTitle = 'Total Livraison expédiée';
        $livraisonInfo = LivraisonInfo::dateFilter()->searchable(['code']);
        $livraisonLists = $livraisonInfo->where('sender_staff_id', $user->id)->whereIn('status', [Status::COURIER_DISPATCH, Status::COURIER_DELIVERYQUEUE, Status::COURIER_DELIVERED])->orderBy('id', 'DESC')
            ->with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo','magasinSection')->paginate(getPaginate());
        return view('staff.livraison.list', compact('pageTitle', 'livraisonLists'));
    }

    public function receivedLivraisonList()
    {
        $user = auth()->user();
        $pageTitle = 'Liste des Livraisons reçues';
        $livraisonLists = LivraisonInfo::where('receiver_staff_id', $user->id)->orderBy('id', 'DESC')->with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo','magasinSection')
            ->paginate(getPaginate());
        return view('staff.livraison.list', compact('pageTitle', 'livraisonLists'));
    }
}
