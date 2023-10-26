<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Designation extends Model
{
    use GlobalStatus;
 
    public function members(): HasMany
    {
        return $this->hasMany(EmployeeTeam::class, 'designation_id');
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(EmployeeDetail::class, 'designation_id');
    }

    public static function allDesignations()
    {
        // if (user()->permission('view_department') == 'all' || user()->permission('view_department') == 'none') {
        //     return Team::all();
        // }

        // return Team::where('added_by', user()->id)->get();
        return Designation::all();
    }

    public function childs(): HasMany
    {
        return $this->hasMany(Designation::class, 'parent_id');
    }
 
}