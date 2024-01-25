<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\Producteur;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProducteurImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    public function rules(): array
    {
        return[
            'nom' => 'required',
            'prenoms' => 'required',
        ];
    }
    public function collection(Collection $collection)
    {
        $cooperatives_id = auth()->user()->cooperative_id;
        $j=0;
        $k='';
        if(count($collection)){
 
        foreach($collection as $row)
         {
      $local_nom = $row['localites']; //Get user names
  $localite = DB::table('localites')->where('nom',$local_nom)->first();
  if($localite !=null)
  {
  $localites_id = $localite->id;
  $coop = DB::table('cooperatives')->where('id', $cooperatives_id)->select('codeApp')->first();
        if($coop !=null)
        { 
        $codeProdapp = $this->generecodeProdApp($row['nom'],$row['prenoms'], $coop->codeApp);

        }else{
          $codeProdapp = '';
        }
  $codeProd = $row['codeproducteur']; //Get the user emails
  if(is_null($codeProd))
  {
    $verification ='';
  }else{
    $verification = DB::table('producteurs')->where('codeProd',$codeProd)->first();
  }
  
if($verification ==null)
{  
  
  
  $agent = DB::table('user_localites')->select('user_id')->where('localite_id', $localites_id)->inRandomOrder()->first();
  if($agent !=null){
    $nationalite = null;
 if($row['nationalite']){
  $nationalite = Str::limit($row['nationalite'], 2, "");
  if($nationalite=='C0'){
    $nationalite="CI";
  }
$nationalite = Country::where('iso',$nationalite)->first();
  if($nationalite !=null){
    $nationalite = $nationalite->id;
  }else{
    $nationalite = null;
  }
 }
      $insert_data = array(
        'localite_id' => $localites_id,
  'codeProd' => $row['codeproducteur'],
  'codeProdapp' => $codeProdapp,
  'nom' => $row['nom'],
  'prenoms' => $row['prenoms'],
  'sexe' => $row['genre'],
  'dateNaiss' => Date::excelToDateTimeObject($row['datenaissance'])->format('Y-m-d'),
  'phone1' => $row['phone1'],
  'phone2' => $row['phone2'],
  'nationalite' => $nationalite,
  'consentement' => $row['consentement'],
  'statut' => $row['statut'],
  'certificat' => $row['anneecertification'],
  'userid' => $agent->user_id,
  'created_at' => NOW(),
  'updated_at' => NOW() 
      );
         
  }else{
    
    $insert_data = array(
      'localite_id' => $localites_id,
'codeProd' => $row['codeproducteur'],
'codeProdapp' => $codeProdapp,
'nom' => $row['nom'],
'prenoms' => $row['prenoms'],
'sexe' => $row['genre'],
'dateNaiss' => Date::excelToDateTimeObject($row['datenaissance'])->format('Y-m-d'),
'phone1' => $row['phone1'],
'phone2' => $row['phone2'],
'consentement' => $row['consentement'],
'statut' => $row['statut'],
'certificat' => $row['anneecertification'],
'userid' => auth()->user()->id,
'created_at' => NOW(),
'updated_at' => NOW() 
    );
  }

  DB::table('producteurs')->insert($insert_data); 
      $j++;
     }
    
 }else{
  $k .=$local_nom.' , ';  
  $notify[] = ['error',"Les Localites dont les noms suivent : $k n'existent pas dans la base."];
        return back()->withNotify($notify);
 }

    }

    if(!empty($j))
    {
      $notify[] = ['success',"$j Producteurs ont été crée avec succès."];
      return back()->withNotify($notify);
    }else{
      $notify[] = ['error',"Aucun Producteur n'a été ajouté à la base car ils existent déjà."];
      return back()->withNotify($notify);
   } 
}else{
  $notify[] = ['error',"Il n'y a aucune données dans le fichier"];
      return back()->withNotify($notify); 
}

    }

    private function generecodeProdApp($nom,$prenoms,$codeApp)
    {
      $action = 'non';

      $data = Producteur::select('codeProdapp')->join('localites as l', 'producteurs.localite_id', '=', 'l.id')->join('sections as s', 'l.section_id', '=', 's.id')->join('cooperatives as c', 's.cooperative_id', '=', 'c.id')->where([
          ['codeProdapp', '!=', null], ['codeApp', $codeApp]
      ])->orderby('producteurs.id', 'desc')->first();

      if ($data != null) {

          $code = $data->codeProdapp;
          if ($code != null) {
              $chaine_number = Str::afterLast($code, '-');
          } else {
              $chaine_number = 0;
          }
      } else {
          $chaine_number = 0;
      }

      $lastCode = $chaine_number + 1;
      $codeP = $codeApp . '-' . gmdate('Y') . '-' . $lastCode;

      do {

          $verif = Producteur::select('codeProdapp')->where('codeProdapp', $codeP)->orderby('id', 'desc')->first();
          if ($verif == null) {
              $action = 'non';
          } else {
              $action = 'oui';
              $code = $codeP;
              $chaine_number = Str::afterLast($code, '-');
              $lastCode = $chaine_number + 1;
              $codeP = $codeApp . '-' . gmdate('Y') . '-' . $lastCode;
          }
      } while ($action != 'non');

      return $codeP;
    }
    
}
