<?php

namespace App\Imports;

use App\Models\Campagne;
use App\Models\Parcelle;
use App\Models\Producteur;
use App\Models\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PhytoImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    public function rules(): array
    {
        return[
            'codeproducteur' => 'required', 
            'codeparcelle' => 'required', 
            'personne_application' => 'required', 
            'superficie_pulverisee' => 'required', 
            'date_application' => 'required',  
        ];
    }
    public function collection(Collection $collection)
    {
        
        $j=0;
        $k='';
        if(count($collection)){
 
        foreach($collection as $row)
         {
           
  $codeProd = $row['codeproducteur'];  
  $codeParc = $row['codeparcelle'];
  $verification = Parcelle::joinRelationship('producteur')->where([['codeProd',$codeProd],['codeParc',$codeParc]])->first();
   
if($verification !=null)
{ 

  $campagne = Campagne::active()->first();
        $application = new Application();
        $application->campagne_id  = $campagne->id;
        $application->parcelle_id  = $verification->id; 
        $application->superficiePulverisee = $row['superficie_pulverisee'];
        $application->delaisReentree = $row['delais_reentree_produit'];
        $application->personneApplication = $row['personne_application'];
        
        $application->date_application = date('Y-m-d', strtotime($row['date_application'])); 
        $application->userid = auth()->user()->id;
        $application->save();
 
      $j++;
     }else{
         $k .= "parcelle $codeParc du producteur $codeProd ,";    
    }

    }

    if(!empty($j))
    {
      $notify[] = ['success',"$j Application(s) ont été crée avec succès"];
      return back()->withNotify($notify); 
     if($k !=''){ 
        $notify[] = ['error',"La $k n'a pas été trouvée."];
      return back()->withNotify($notify); 
     }
     
    }else{
        if($k !=''){
             
            $notify[] = ['error',"La $k n'a pas été trouvée."];
      return back()->withNotify($notify); 
         } 
   } 
}else{
    
    $notify[] = ['error',"Il n'y a aucune données dans le fichier"];
      return back()->withNotify($notify); 
}

    }

 
    
}
