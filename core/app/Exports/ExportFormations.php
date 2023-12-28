<?php

namespace App\Exports;
 
use Maatwebsite\Excel\Concerns\WithMultipleSheets;  

class ExportFormations implements WithMultipleSheets
{
     
  public function sheets(): array 
    {    
      $feuilles=array();  
      $feuilles[] = new FormationProducteursExport();
      $feuilles[] = new FormationThemesExport();   
      $feuilles[] = new FormationVisiteursExport();  

    $sheets = [ new FormationsExport(), ];
    $sheets = array_merge($sheets, $feuilles);
   

    return $sheets; 
    }
  
}
