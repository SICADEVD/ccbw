<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class ActiviteCommunautaire extends Model
{
    use Searchable, GlobalStatus, PowerJoins;

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function localite()
    {
        return $this->belongsTo(Localite::class);
    }
}
