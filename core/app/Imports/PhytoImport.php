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
        ];
    }
    public function collection(Collection $collection)
    {
        
        $j=0;
        $k='';
        if(count($collection)){
 
        foreach($collection as $row)
         {
          dd($row); 
  $codeProd = $row['codeproducteur'];  
  $codeParc = $row['codeparcelle'];
    $donnee = $row['qui_a_realise_lapplication'];
    $donnee = $row['applicateur'];
    $donnee = $row['a_t_il_suivi_une_formation'];
    $donnee = $row['a_t_il_une_attestation'];
    $donnee = $row['a_t_il_fait_un_bilan_de_sante'];
    $donnee = $row['possede_t_il_un_epi'];
    $donnee = $row['pesticides'];
    $donnee = $row['nom_commercial'];
    $donnee = $row['matieres_actives'];
    $donnee = $row['toxicicologie'];
    $donnee = $row['dose'];
    $donnee = $row['unite_dose'];
    $donnee = $row['quantite'];
    $donnee = $row['unite_quantite'];
    $donnee = $row['frequence'];
    $donnee = $row['maladies_observees_dans_la_parcelle'];
    $superficie_pulverisee = $row['superficie_pulverisee'];
    $donnee = $row['delais_de_reentree_du_produit_en_jours'];
    $duree_dapplication = $row['duree_dapplication'];
    $date_dapplication = $row['date_dapplication'];
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
