<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory, Searchable, GlobalStatus;

    protected $guarded = [];

    public function localite()
    {
        return $this->belongsTo(Localite::class, 'localite_id');
    }
}
