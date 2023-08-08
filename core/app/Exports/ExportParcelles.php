<?php

namespace App\Exports;

use App\Models\Parcelle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ExportParcelles implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function view(): View
    {
        // TODO: Implement view() method.
        return view('manager.parcelle.ParcellesAllExcel',[
            'parcelles' => Parcelle::joinRelationship('producteur.localite')->where('cooperative_id',auth()->user()->cooperative_id)->get()
        ]);
    }
        
}
