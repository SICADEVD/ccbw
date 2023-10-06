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

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id');
    } 
	
    public function localites()
    {
        return $this->hasMany(Localite::class, 'section_id', 'id');
    }
}
