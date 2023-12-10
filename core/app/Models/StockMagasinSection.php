<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
use Kirschbaum\PowerJoins\PowerJoins;

class StockMagasinSection extends Model
{

    use Searchable, GlobalStatus, PowerJoins;
    protected $table="stock_magasin_sections";

    public function producteur()
    {
        return $this->belongsTo(Producteur::class, 'producteur_id');
    }
    
    public function campagne()
    {
        return $this->belongsTo(Campagne::class, 'campagne_id');
    }
    public function campagnePeriode()
    {
        return $this->belongsTo(CampagnePeriode::class, 'campagne_periode_id');
    }
    public function magasinSection()
    {
        return $this->belongsTo(MagasinSection::class, 'magasin_section_id');
    }
}