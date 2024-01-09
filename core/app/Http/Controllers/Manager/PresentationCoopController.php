<?php

namespace App\Http\Controllers\Manager;

use App\Models\Parcelle;
use App\Models\Producteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PresentationCoopController extends Controller
{
    public function index()
    {
        $nombreProducteur = Producteur::whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();
        $hommes = Producteur::where('sexe', 'Homme')->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        $femmes = Producteur::where('sexe', 'Femme')->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        $countProducteursRainforest = Producteur::whereHas('certifications', function ($query) {
            $query->where('certification', 'RAINFOREST');
        })->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        $countProducteursRainforestHomme = Producteur::where('sexe', 'Homme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'RAINFOREST');
        })->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        $countProducteursRainforestFemme = Producteur::where('sexe', 'Femme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'RAINFOREST');
        })->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        $countProducteursFairtrade = Producteur::whereHas('certifications', function ($query) {
            $query->where('certification', 'FAIRTRADE');
        })->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        $countProducteursFairtradeHomme = Producteur::where('sexe', 'Homme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'FAIRTRADE');
        })->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        $countProducteursFairtradeFemme = Producteur::where('sexe', 'Femme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'FAIRTRADE');
        })->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        $countProducteursBio = Producteur::whereHas('certifications', function ($query) {
            $query->where('certification', 'BIO');
        })->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        $countProducteursBioHomme = Producteur::where('sexe', 'Homme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'BIO');
        })->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        $countProducteursBioFemme = Producteur::where('sexe', 'Femme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'BIO');
        })->whereHas('programme', function ($query) {
            $query->where('libelle', 'Bandama');
        })->count();

        //producteurs programme bandama

        $nombreProducteurAutreProgramme = Producteur::whereHas('programme', function ($query) {
            $query->where('libelle', 'Aucun programme');
        })->count();
        $hommesAutrePragramme = Producteur::where('sexe', 'Homme')->whereHas('programme', function ($query) {
            $query->where('libelle', 'Aucun programme');
        })->count();

        $femmesAutreProgramme = Producteur::where('sexe', 'Femme')->whereHas('programme', function ($query) {
            $query->where('libelle', 'Aucun programme');
        })->count();

        //parcelle des producteurs
        $sumSuperficie = Parcelle::join('producteurs', 'parcelles.producteur_id', '=', 'producteurs.id')
        ->sum('parcelles.superficie');
    
        return view('manager.presentation-coop.index', compact('nombreProducteur', 'hommes', 'femmes', 'countProducteursRainforest', 'countProducteursFairtrade', 'countProducteursBio', 'countProducteursRainforestHomme', 'countProducteursRainforestFemme', 'countProducteursFairtradeHomme', 'countProducteursFairtradeFemme', 'countProducteursBioHomme', 'countProducteursBioFemme', 'nombreProducteurAutreProgramme', 'hommesAutrePragramme', 'femmesAutreProgramme', 'sumSuperficie'));
    }

    public function create()
    {
        return view('manager.presentation-coop.create');
    }

    public function edit()
    {
        return view('manager.presentation-coop.edit');
    }
    public function store(Request $request)
    {
        return redirect()->route('manager.presentation-coop.index');
    }
}
