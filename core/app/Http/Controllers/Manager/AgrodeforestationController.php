<?php

namespace App\Http\Controllers\Manager;

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
        $manager   = auth()->user(); 

        $sections = Section::where('cooperative_id', $manager->cooperative_id)->get();
     
        $localites = Localite::joinRelationship('section')
                                ->where('cooperative_id', $manager->cooperative_id)
                                ->when(request()->section, function ($query, $section) {
                                    $query->where('section_id', $section);
                                })
                                ->get(); 
        $producteurs = Producteur::joinRelationship('localite.section')
                                    ->where([['cooperative_id', $manager->cooperative_id],['producteurs.status',1]])
                                    ->when(request()->localite, function ($query, $localite) {
                                        $query->where('localite_id', $localite);
                                    }) 
                                    ->get();

        $parcelles = Parcelle::dateFilter()->latest('id')
            ->joinRelationship('producteur.localite.section')
            ->where([['cooperative_id', $manager->cooperative_id],['typedeclaration','GPS']])
            ->whereNotNull('waypoints')
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
         
        return view('manager.deforestation.index',compact('pageTitle','sections', 'parcelles', 'localites','producteurs','foretclassees','foretclasseetampons'));
    }
 
    public function waypoints()
    {
        $manager   = auth()->user();
 
        $cooperative = Cooperative::with('sections.localites')->find($manager->cooperative_id);

        $sections = Section::where('cooperative_id', $manager->cooperative_id)->get();
     
        $localites = Localite::joinRelationship('section')
                                ->where('cooperative_id', $manager->cooperative_id)
                                ->when(request()->section, function ($query, $section) {
                                    $query->where('section_id', $section);
                                })
                                ->get(); 
        $producteurs = Producteur::joinRelationship('localite.section')
                                    ->where([['cooperative_id', $manager->cooperative_id],['producteurs.status',1]])
                                    ->when(request()->localite, function ($query, $localite) {
                                        $query->where('localite_id', $localite);
                                    })
                                    ->get();

        $parcelles = Parcelle::dateFilter()->latest('id')
            ->joinRelationship('producteur.localite.section')
            ->where('cooperative_id', $manager->cooperative_id) 
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
         
        return view('manager.deforestation.waypoints',compact('pageTitle','sections', 'parcelles', 'localites','producteurs','foretclassees','foretclasseetampons'));
    }
     
}
