<?php

namespace App\Exports;
 
use App\Models\SuiviFormationVisiteur;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class FormationVisiteursExport implements FromView, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function view(): View
    {
        // TODO: Implement view() method.
        
        return view('manager.formation.FormationsVisiteurExcel',[
            'visiteurs' => SuiviFormationVisiteur::joinRelationship('suiviFormation.localite')->where('cooperative_id',auth()->user()->cooperative_id)->get()
        ]);
    }

    public function title(): string
    {
        Return "Formation Visiteurs";
    }
}
