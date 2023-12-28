<?php

namespace App\Exports;

use App\Models\SuiviFormation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class FormationsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function view(): View
    {
        // TODO: Implement view() method.
        return view('manager.formation.FormationsAllExcel',[
            'formations' => SuiviFormation::joinRelationship('localite.section')->where('cooperative_id',auth()->user()->cooperative_id)
            ->when(request()->id, function ($query, $id) {
                $query->where('suivi_formations.id',decrypt($id)); 
           })
            ->get()
        ]);
    } 
}
