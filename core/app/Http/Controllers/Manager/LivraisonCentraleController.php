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
use Illuminate\Support\Str;
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
use App\Models\ConnaissementProduit;
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

        $data = Connaissement::orderby('id','desc')->limit(1)->get();

        if(count($data)>0){
            $code = $data[0]->numeroCU;
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
            'sender_transporteur' =>  'required', 
            'sender_vehicule' =>  'required',   
			'type' => 'required', 
            'estimate_date'    => 'required|date|date_format:Y-m-d', 
        ]);

        $manager = auth()->user();
        $livraison = new Connaissement(); 
        $campagne = Campagne::active()->first();
        $periode = CampagnePeriode::where([['campagne_id',$campagne->id],['periode_debut','<=', gmdate('Y-m-d')],['periode_fin','>=', gmdate('Y-m-d')]])->latest()->first();
        
        $livraison->cooperative_id   = $manager->cooperative_id;
        $livraison->campagne_id    = $campagne->id;
        $livraison->campagne_periode_id = $periode->id;
        $livraison->magasin_centraux_id = $request->magasin_central;
        $livraison->numeroCU = $request->code;
        $livraison->type_produit = json_encode($request->type);
        $livraison->quantite_livre = $request->poidsnet;
        $livraison->sacs_livre = $request->nombresacs; 
        $livraison->transporteur_id = $request->sender_transporteur;
        $livraison->vehicule_id = $request->sender_vehicule;
        $livraison->date_livraison = $request->estimate_date;
       
        $livraison->save();
 
        $i=0;
        $data = [];
        $quantite = $request->quantite; 
        $typeproduit = $request->typeproduit; 
        $producteurs = $request->producteurs;
        $stock_magasin_central = $request->stock_magasin_central;
        foreach($producteurs as $item) { 
            
            if($quantite[$i]>0)
            {
            $data[] = [
                'connaissement_id' => $livraison->id,
                'producteur_id' => $item,
                'campagne_id' => $campagne->id,
                'campagne_periode_id' => $periode->id,
                'quantite' => $quantite[$i], 
                'stock_magasin_central_id' => $stock_magasin_central[$i], 
                'type_produit' => $typeproduit[$i],
                'created_at'      => now(),
            ];
            $prod = LivraisonMagasinCentralProducteur::where([['campagne_id',$campagne->id],['stock_magasin_central_id',$stock_magasin_central[$i]],['producteur_id',$item],['type_produit',$typeproduit[$i]]])->first();
            if($prod !=null){ 
                 
            $prod->quantite = $prod->quantite - $quantite[$i];
            $prod->quantite_restant = $prod->quantite_restant + $quantite[$i];
           
            $prod->save();
            }

            $stockCent = StockMagasinCentral::where('id',$stock_magasin_central[$i])->first();
            if($stockCent !=null){ 
                 
            $stockCent->stocks_mag_entrant = $stockCent->stocks_mag_entrant - $quantite[$i];
            $stockCent->stocks_mag_sacs_entrant = $stockCent->stocks_mag_sacs_entrant + $quantite[$i];
           
            $stockCent->save();
            }
        }
            $i++;
        }
		

        ConnaissementProduit::insert($data); 

        $notify[] = ['success', 'Le connaissement vers l\'Usine a été ajouté avec succès'];
        return to_route('manager.livraison.usine.stock')->withNotify($notify);
    }
 
    public function getProducteur(){
        $input = request()->all();
         
        $id = $input['magasin_central'];
        $campagne = Campagne::active()->first();
        $periode = CampagnePeriode::where([['campagne_id',$campagne->id]])->latest()->first();

        $stocks = LivraisonMagasinCentralProducteur::joinRelationship('stockMagasinCentral')->where([['livraison_magasin_central_producteurs.campagne_id',$campagne->id],['magasin_centraux_id',$id],['quantite','>',0]])->whereIn('livraison_magasin_central_producteurs.type_produit', request()->type)->select('stock_magasin_centraux.*')->groupBy('numero_connaissement')->get();
        if ($stocks->count()) {
            $contents = '';

            foreach ($stocks as $data) {
               
                $contents .= '<option value="'.$data->id.'">'. $data->numero_connaissement .'</option>';
            }
        } else {
            $contents = null;
        }
 
        return $contents;
    }

    public function getListeProducteurConnaiss(){
        $input = request()->all();
          $magasinsection= $input['magasin_central']; 
          $codes = $input['connaissement_id'];
          $type_produit = $input['type'];
          $results ='';
          $total = 0;
          $totalsacs=0;
          $campagne = Campagne::active()->first();
        $stock =LivraisonMagasinCentralProducteur::joinRelationship('stockMagasinCentral')->where([['livraison_magasin_central_producteurs.campagne_id',$campagne->id],['stock_magasin_centraux.magasin_centraux_id',$magasinsection],['stocks_mag_entrant','>',0]])->whereIn('livraison_magasin_central_producteurs.type_produit', $type_produit)->whereIn('stock_magasin_central_id', $codes)->select('livraison_magasin_central_producteurs.*')->get();
       
            if(count($stock)){
            $v=1;
            $tv=count($stock);
       foreach($stock as $data)
       {
         if($v==$tv){$read = '';}
         else{$read='readonly';}
        $results .= '<tr><td colspan="2"><h5>'.$data->producteur->nom.' '.$data->producteur->prenoms.'('.$data->producteur->codeProdapp.')</h5><input type="hidden" name="producteurs[]" value="'.$data->producteur_id.'"/></td><td style="width: 300px;"><input type="hidden" name="typeproduit[]" value="'.$data->type_produit.'"/>'.$data->type_produit.'<input type="hidden" name="stock_magasin_central[]" value="'.$data->stock_magasin_central_id.'"/></td><td style="width: 400px;"> <input type="number" name="quantite[]" value="'.$data->quantite.'" min="0" max="'.$data->quantite.'"  class="form-control quantity" style="width: 115px;"/></td></tr>';
        $total = $total+$data->quantite;
        // $totalsacs = $totalsacs+$data->nb_sacs_entrant;
        $v++;
        }
  
  
            }
            $contents['results'] = $results;
            $contents['total'] = $total;
            $contents['totalsacs'] = $totalsacs;
  
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
