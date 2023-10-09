<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProducteurRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'programme_id' => 'required|exists:programmes,id',
            'proprietaires' => 'required',
            'certificats' => 'required',
            'variete' => 'required',
            'habitationProducteur' => 'required',
            'statut' => 'required',
            'statutMatrimonial' => 'required',
            'localite_id'    => 'required|exists:localites,id',
            'nom' => 'required|max:255',
            'prenoms'  => 'required|max:255',
            'sexe'  => 'required|max:255',
            'nationalite'  => 'required|max:255',
            'dateNaiss'  => 'required|max:255',
            'phone1'  => 'required|max:255',
            'niveau_etude'  => 'required|max:255',
            'type_piece'  => 'required|max:255',
            'numPiece'  => 'required|max:255',
            'anneeDemarrage' =>'required_if:proprietaires,==,Garantie',
            'anneeFin' =>'required_if:proprietaires,==,Garantie',
            'plantePartage'=>'required_if:proprietaires,==,Planté-partager',
            'typeCarteSecuriteSociale'=>'required',
            'autreCertificats'=>'required_if:certificats,==,Autre',
            'autreVariete'=>'required_if:variete,==,Autre',
            'codeProd'=>'required_if:statut,==,Certifie',
            'certificat'=>'required_if:statut,==,Certifie',
            'phone2'=>'required_if:autreMembre,==,oui',
            'autrePhone'=>'required_if:autreMembre,==,oui',
            'numCMU'=>'required_if:carteCMU,==,oui',
        ];
    }
    public function messages()
    {
        return [
            'programme_id.required' => 'Le programme est obligatoire',
            'proprietaires.required' => 'Le type de propriétaire est obligatoire',
            'certificats.required' => 'Le type de certificat est obligatoire',
            'variete.required' => 'Le type de variété est obligatoire',
            'habitationProducteur.required' => 'Le type d\'habitation est obligatoire',
            'statut.required' => 'Le statut est obligatoire',
            'statutMatrimonial.required' => 'Le statut matrimonial est obligatoire',
            'localite_id.required' => 'La localité est obligatoire',
            'nom.required' => 'Le nom est obligatoire',
            'prenoms.required' => 'Le prénom est obligatoire',
            'sexe.required' => 'Le sexe est obligatoire',
            'nationalite.required' => 'La nationalité est obligatoire',
            'dateNaiss.required' => 'La date de naissance est obligatoire',
            'phone1.required' => 'Le numéro de téléphone est obligatoire',
            'niveau_etude.required' => 'Le niveau d\'étude est obligatoire',
            'type_piece.required' => 'Le type de pièce est obligatoire',
            'numPiece.required' => 'Le numéro de pièce est obligatoire',
            'num_ccc.unique' => 'Le numéro de CCC existe déjà',
            'anneeDemarrage.required_if' => 'L\'année de démarrage est obligatoire',
            'anneeFin.required_if' => 'L\'année de fin est obligatoire',
            'plantePartage.required_if' => 'Le type de plante est obligatoire',
            'typeCarteSecuriteSociale.required' => 'Le type de carte de sécurité sociale est obligatoire',
            'autreCertificats.required_if' => 'Le type de certificat est obligatoire',
            'autreVariete.required_if' => 'Le type de variété est obligatoire',
            'codeProdapp.required_if' => 'Le code Prodapp est obligatoire',
            'certificat.required_if' => 'Le certificat est obligatoire',
            'phone2.required_if' => 'Le numéro de téléphone est obligatoire',
            'autrePhone.required_if' => 'Le champ membre de famille est obligatoire',
        ];
    }
    public function attributes()
    {
        return [
            'programme_id' => 'programme',
            'proprietaires' => 'propriétaire',
            'certificats' => 'certificat',
            'variete' => 'variété',
            'habitationProducteur' => 'habitation',
            'statut' => 'statut',
            'statutMatrimonial' => 'statut matrimonial',
            'localite_id'    => 'localité',
            'nom' => 'nom',
            'prenoms'  => 'prénom',
            'sexe'  => 'sexe',
            'nationalite'  => 'nationalité',
            'dateNaiss'  => 'date de naissance',
            'phone1'  => 'numéro de téléphone',
            'niveau_etude'  => 'niveau d\'étude',
            'type_piece'  => 'type de pièce',
            'numPiece'  => 'numéro de pièce',
            'num_ccc' => 'numéro de CCC',
            'anneeDemarrage' =>'année de démarrage',
            'anneeFin' =>'année de fin',
            'plantePartage'=>'Planté-partager',
            'typeCarteSecuriteSociale'=>'type de carte de sécurité sociale',
            'autreCertificats'=>'type de certificat',
            'autreVariete'=>'Autre variété',
            'codeProdapp'=>'code Prodapp',
            'certificat'=>'certificat',
            'phone2'=>'numéro de téléphone',
            'autrePhone'=>'membre de famille',
            'numCMU'=>'numéro de CMU',
        ];
    }
}
