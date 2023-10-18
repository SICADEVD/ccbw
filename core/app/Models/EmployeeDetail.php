<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class EmployeeDetail extends Model
{
    use Searchable, GlobalStatus, PowerJoins;

    //protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
  
    public function userBadge()
    {
        $itsYou = ' <span class="ml-2 badge badge-secondary pr-1">' . __('app.itsYou') . '</span>';

        if (auth()->user() && auth()->user()->id == $this->id) {
            return $this->name . $itsYou;
        }

        return $this->name;
    }
     
}