<?php

namespace App\Http\Controllers\Admin;
 
use App\Models\Section; 
use App\Models\Localite;
use App\Models\Parcelle; 
use App\Models\Producteur;
use App\Models\Cooperative;  
use App\Http\Controllers\Controller;  

class ParcelleController extends Controller
{
 
    public function mapping()
    {
        $pageTitle      = "Gestion de mapping des parcelles"; 

        $cooperatives = Cooperative::get();

        $sections = Section::when(request()->cooperative, function ($query, $cooperative) {
                            $query->where('cooperative_id', $cooperative);
                        })
                        ->with('cooperative')
                        ->get();

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
            ->with(['producteur.localite.section']) // Charger les relations nécessaires
            ->get();

        return view('admin.parcelle.mapping', compact('pageTitle','cooperatives', 'sections', 'parcelles', 'localites', 'producteurs'));
    }
    public function mappingPolygone()
    { 

        $cooperatives = Cooperative::get();

        $sections = Section::when(request()->cooperative, function ($query, $cooperative) {
            $query->where('cooperative_id', $cooperative);
        })->with('cooperative')->get();

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
            ->with(['producteur.localite.section'])
            ->get();
        $total = count($parcelles);
        $pageTitle  = "Gestion de mapping des parcelles($total)";
        return view('admin.parcelle.mapping-trace', compact('pageTitle','cooperatives', 'sections', 'parcelles', 'localites', 'producteurs'));
    }
 
}
