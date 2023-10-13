<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class User_localite extends Model
{
    public function section(){
        return $this->belongsTo(Section::class,'section_id');
    }
}
