<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
use Kirschbaum\PowerJoins\PowerJoins;

class LivraisonMagasinCentralProducteur extends Model
{

    use Searchable, GlobalStatus, PowerJoins;
    protected $table="livraison_magasin_central_producteurs";

    public function producteur()
    {
        return $this->belongsTo(Producteur::class, 'producteur_id');
    }
     
    public function stockMagasinCentral()
    {
        return $this->belongsTo(StockMagasinCentral::class, 'stock_magasin_central_id');
    } 
}