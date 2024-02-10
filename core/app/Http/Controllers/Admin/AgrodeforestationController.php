<?php

namespace App\Http\Controllers\Admin;

use Excel;
use App\Models\Section;
use App\Constants\Status;
use App\Models\Localite; 
use App\Models\Parcelle; 
use App\Models\Cooperative;
use App\Models\Producteur; 
use Illuminate\Support\Str;
use App\Models\ForetClassee;
use Illuminate\Http\Request;
use App\Imports\ParcelleImport;
use App\Exports\ExportParcelles;
use App\Models\ForetClasseeTampon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AgrodeforestationController extends Controller
{

    public function index()
    { 
        $cooperatives = Cooperative::get();

        $sections = Section::when(request()->cooperative, function ($query, $cooperative) {
            $query->where('cooperative_id', $cooperative);
        })->get();
     
        $localites = Localite::joinRelationship('section')
                                ->when(request()->cooperative, function ($query, $cooperative) {
                                    $query->where('cooperative_id', $cooperative);
                                })
                                ->when(request()->section, function ($query, $section) {
                                    $query->where('section_id', $section);
                                })
                                ->get(); 
        $producteurs = Producteur::joinRelationship('localite.section')
                                    ->when(request()->cooperative, function ($query, $cooperative) {
                                        $query->where('cooperative_id', $cooperative);
                                    })
                                    ->when(request()->localite, function ($query, $localite) {
                                        $query->where('localite_id', $localite);
                                    }) 
                                    ->get();

        $parcelles = Parcelle::dateFilter()->latest('id')
            ->joinRelationship('producteur.localite.section') 
            ->whereNotNull('waypoints')
            ->when(request()->cooperative, function ($query, $cooperative) {
                $query->where('cooperative_id', $cooperative);
            })
            ->when(request()->section, function ($query, $section) {
                $query->where('section_id', $section);
            })
            ->when(request()->localite, function ($query, $localite) {
                $query->where('localite_id', $localite);
            })
            ->when(request()->producteur, function ($query, $producteur) {
                $query->where('producteur_id', $producteur);
            })
            ->with(['producteur.localite.section.cooperative']) 
            ->get();
            $total = count($parcelles);
            $foretclassees = ForetClassee::get();
            $foretclasseetampons = ForetClasseeTampon::get();
            $pageTitle  = "Risque de Deforestation par Polygones($total)";
         
        return view('admin.deforestation.index',compact('pageTitle','cooperatives','sections', 'parcelles', 'localites','producteurs','foretclassees','foretclasseetampons'));
    }
 
    public function waypoints()
    {
        $manager   = auth()->user();
 
        $cooperatives = Cooperative::get();

        $sections = Section::when(request()->cooperative, function ($query, $cooperative) {
            $query->where('cooperative_id', $cooperative);
        })->get();
     
        $localites = Localite::joinRelationship('section') 
                                ->when(request()->cooperative, function ($query, $cooperative) {
                                    $query->where('cooperative_id', $cooperative);
                                })
                                ->when(request()->section, function ($query, $section) {
                                    $query->where('section_id', $section);
                                })
                                ->get(); 
        $producteurs = Producteur::joinRelationship('localite.section') 
                                    ->when(request()->cooperative, function ($query, $cooperative) {
                                        $query->where('cooperative_id', $cooperative);
                                    })
                                    ->when(request()->localite, function ($query, $localite) {
                                        $query->where('localite_id', $localite);
                                    })
                                    ->get();

        $parcelles = Parcelle::dateFilter()->latest('id')
            ->joinRelationship('producteur.localite.section') 
            ->when(request()->cooperative, function ($query, $cooperative) {
                $query->where('cooperative_id', $cooperative);
            })
            ->when(request()->section, function ($query, $section) {
                $query->where('section_id', $section);
            })
            ->when(request()->localite, function ($query, $localite) {
                $query->where('localite_id', $localite);
            })
            ->when(request()->producteur, function ($query, $producteur) {
                $query->where('producteur_id', $producteur);
            })
            ->with(['producteur.localite.section']) 
            ->get();
            $total = count($parcelles);
            $foretclassees = ForetClassee::get();
            $foretclasseetampons = ForetClasseeTampon::get();
            $pageTitle  = "Risque de Deforestation par Waypoints($total)";
         
        return view('admin.deforestation.waypoints',compact('pageTitle','cooperatives','sections', 'parcelles', 'localites','producteurs','foretclassees','foretclasseetampons'));
    }
     
}
