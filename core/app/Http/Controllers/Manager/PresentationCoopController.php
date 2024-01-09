<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use Illuminate\Http\Request;

class PresentationCoopController extends Controller
{
    public function index()
    {
        $nombreProducteur = Producteur::count();
        $hommes = Producteur::where('sexe', 'Homme')->count();
        $femmes = Producteur::where('sexe', 'Femme')->count();

        $countProducteursRainforest = Producteur::whereHas('certifications', function ($query) {
            $query->where('certification', 'RAINFOREST');
        })->count();

        $countProducteursRainforestHomme = Producteur::where('sexe', 'Homme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'RAINFOREST');
        })->count();

        $countProducteursRainforestFemme = Producteur::where('sexe', 'Femme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'RAINFOREST');
        })->count();

        $countProducteursFairtrade = Producteur::whereHas('certifications', function ($query) {
            $query->where('certification', 'FAIRTRADE');
        })->count();

        $countProducteursFairtradeHomme = Producteur::where('sexe', 'Homme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'FAIRTRADE');
        })->count();

        $countProducteursFairtradeFemme = Producteur::where('sexe', 'Femme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'FAIRTRADE');
        })->count();

        $countProducteursBio = Producteur::whereHas('certifications', function ($query) {
            $query->where('certification', 'BIO');
        })->count();

        $countProducteursBioHomme = Producteur::where('sexe', 'Homme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'BIO');
        })->count();

        $countProducteursBioFemme = Producteur::where('sexe', 'Femme')->whereHas('certifications', function ($query) {
            $query->where('certification', 'BIO');
        })->count();
        

        return view('manager.presentation-coop.index', compact('nombreProducteur', 'hommes', 'femmes', 'countProducteursRainforest', 'countProducteursFairtrade', 'countProducteursBio', 'countProducteursRainforestHomme', 'countProducteursRainforestFemme', 'countProducteursFairtradeHomme', 'countProducteursFairtradeFemme', 'countProducteursBioHomme', 'countProducteursBioFemme'));
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
