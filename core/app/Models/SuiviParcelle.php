<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class SuiviParcelle extends Model
{
    use Searchable, GlobalStatus, PowerJoins;

    //protected $guarded = [];

    public function campagne()
    {
        return $this->belongsTo(Campagne::class);
    }

    public function parcelle()
    {
        return $this->belongsTo(Parcelle::class);
    }

    public function parasite()
    {
        return $this->hasMany(SuiviParcellesParasite::class, 'suivi_parcelle_id', 'id');
    }
    public function ombrage()
    {
        return $this->hasMany(SuiviParcellesOmbrage::class, 'suivi_parcelle_id', 'id');
    }
    public function animal()
    {
        return $this->hasMany(SuiviParcellesAnimal::class, 'suivi_parcelle_id', 'id');
    }
    public function agroforesterie()
    {
        return $this->hasMany(SuiviParcellesAgroforesterie::class, 'suivi_parcelle_id', 'id');
    }
    public function insectes(){
        return $this->hasMany(SuiviParcellesInsecte::class, 'suivi_parcelle_id', 'id');
    }
     
     
}