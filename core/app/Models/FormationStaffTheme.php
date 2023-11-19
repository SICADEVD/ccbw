<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class FormationStaffTheme extends Model
{
    use Searchable, GlobalStatus, PowerJoins; 
    
    protected $table="formation_staff_themes";

    public function formationStaff()
    {
        return $this->belongsTo(FormationStaff::class);
    }

    public function theme()
    {
        return $this->belongsTo(ThemeFormationStaff::class,'theme_formation_staff_id');
    }
     
     
}