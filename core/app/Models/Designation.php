<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Designation extends Model
{
    use GlobalStatus;
 
    public function departement(){

        return $this->belongsTo(Department::class,'department_id');
    }
}