<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suivi_parcelle_pesticide extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'suivi_parcelle_pesticides';

    public function suivi_parcelle()
    {
        return $this->belongsTo(SuiviParcelle::class, 'suivi_parcelle_id', 'id');
    }
}
