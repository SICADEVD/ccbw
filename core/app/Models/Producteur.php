<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Producteur extends Model
{
    use Searchable, GlobalStatus, PowerJoins;

    protected $guarded = [];

    public function localite()
    {
        return $this->belongsTo(Localite::class, 'localite_id');
    }
    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }
    public function menages()
    {
        return $this->hasMany(Menage::class, 'producteur_id', 'id');
    }
    
}