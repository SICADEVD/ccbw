<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Parcelle extends Model
{
    use Searchable, GlobalStatus, PowerJoins;
    protected $guarded = [];

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }
     
     
}