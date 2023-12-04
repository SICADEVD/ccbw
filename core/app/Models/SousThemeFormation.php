<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;

class SousThemeFormation extends Model
{
    use HasFactory, PowerJoins, GlobalStatus, Searchable;

    protected $table = 'sous_themes_formations';

    public function themeFormation()
    {
        return $this->belongsTo(ThemesFormation::class, 'theme_formation_id');
    }
}
