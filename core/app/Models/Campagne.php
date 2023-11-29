<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\HasCooperative;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Campagne extends Model
{
    use GlobalStatus;
    use HasCooperative;
}