<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;
use Dp0\UserActivity\Traits\UserActivity;

class Parcelle extends Model
{
    use Searchable, GlobalStatus, PowerJoins, UserActivity;
    
    protected $guarded = ['section','localite',];

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }
    
    public function parcelleTypeProtections()
    {
        return $this->hasMany(Parcelle_type_protection::class,'parcelle_id');
    }
    public function agroespeceabre_parcelles()
    {
        return $this->hasMany(agroespeceabre_parcelle::class,'parcelle_id');
    }
}