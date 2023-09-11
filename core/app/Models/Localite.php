<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Localite extends Model
{
    use Searchable, GlobalStatus, PowerJoins;

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id');
    }
    
    public function ecoleprimaires()
    {
        return $this->hasMany(Localite_ecoleprimaire::class,'localite_id', 'id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function producteurs()
    {
        return $this->hasMany(Producteur::class, 'localite_id', 'id');
    }
}