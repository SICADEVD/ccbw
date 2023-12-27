<?php

namespace App\Exports;

use App\Models\Producteur_info;
use App\Models\Producteur_infos_maladieenfant;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class InfosMaladiesEnfantExport implements FromView, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function view(): View
    {
        // TODO: Implement view() method.
        
        return view('manager.producteur.InfosMaladiesEnfantExcel',[
            'maladies' => Producteur_infos_maladieenfant::joinRelationship('producteurInfo.producteur.localite.section')->where('cooperative_id',auth()->user()->cooperative_id)->get()
        ]);
    }

    public function title(): string
    {
        Return "Infos Maladies Enfant";
    }
}
