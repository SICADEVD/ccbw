<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Designation extends Model
{
    use GlobalStatus;
 
    
    public function members()
    {
        return $this->hasMany(EmployeeDetails::class, 'designation_id');
    }

    public static function allDesignations()
    {
        // if (user()->permission('view_designation') == 'all' || user()->permission('view_designation') == 'none') {
        //     return Designation::all();
        // }

        // return Designation::where('added_by', user()->id)->get();
        return Designation::all();
    }

    public function childs()
    {
        return $this->hasMany(Designation::class, 'parent_id')->with('childs');
    }
}