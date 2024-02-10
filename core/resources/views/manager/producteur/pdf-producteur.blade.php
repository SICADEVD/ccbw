<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
<style>
 table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table, th, td {
    border: 1px solid black;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

/* Style for odd rows */
tr:nth-child(odd) {
    background-color: #e6e6e6;
}

/* Add additional styling as needed */

</style>
</head>
<body>
<table>

<tr>
    <td>Accord de consentement du producteur
    </td>
<td>
        {{ @$producteur->consentement }}
		 
    </td>
</tr> 

<tr>
    <td>Comment vous vous definissez ?
    </td>
<td>
        {{ @$producteur->proprietaires }}
 
    </td>
</tr>
<tr>
    <td>
    </td>
<td>
        {{ @$producteur->plantePartage }}
 
    </td>
</tr>
 
    <tr>
        <td>Année de démarrage
        </td>
<td>
            {{ @$producteur->anneeDemarrage }}
 
        </td>
</tr>
    <tr>
        <td>Année de fin
        </td>
<td>
            {{ @$producteur->anneeFin }}
 
        
    </td>
</tr>
<tr>
    <td>Statut
    </td>
<td>
        {{ @$producteur->statut }}
 
    </td>
</tr>
                
    <tr>
        <td>Année de certification
        </td>
<td>
            {{ @$producteur->certificat }}
 
        </td>
</tr>
    <tr>
        <td>Code producteur
        </td>
<td>
            {{ @$producteur->codeProd }}
 
        </td>
</tr>
   
    <tr>
        <td>Certificat

        </td>
<td>
            {{ @$producteur->certificats }} 
        
    </td>
</tr>

    <tr>
        <td>Autre Certificat
        </td>
<td>
            {{ @$producteur->autreCertificats }} 
        
    </td>
</tr>
                  
<tr>
<td>
    Section
    </td>
 
<td>
        {{ @$producteur->section }} 
    </td>
</tr>
 
<tr>
<td>
    Localite
    </td>
<td>
        {{ @$producteur->localite_id }} 
    </td>
</tr>
 
<tr>
	<td>
    Programme
    </td>
<td>
        {{ @$producteur->programme_id }} 
    </td>
</tr> 
<tr>
    <td>Habitez-vous dans un campement ou village ?
    </td>
<td>
        {{ @$producteur->habitationProducteur }} 
    </td>
</tr> 
<tr>
    <td>Nom du producteur
    </td>
<td>
        {{ @$producteur->nom }}
</td>
</tr>

<tr>
    <td>Prenoms du producteur
    </td>
<td>
        {{ @$producteur->prenoms }}
    </td>
</tr>

<tr>
    <td>Genre
    </td>
<td>
        {{ @$producteur->sexe }}
</td>
</tr>
                  
<tr>
    <td>Statut matrimonial
    </td>
<td>
        {{ @$producteur->statutMatrimonial }} 
    </td>
</tr>

<tr>
    <td>Nationalité
    </td>
<td>
        {{ @$producteur->nationalite }} 
        </select>
    </td>
</tr>

<tr>
    <td>Date de naissance
    </td>
<td>
        {{ @$producteur->dateNaiss }} 
    </td>
</tr>
<tr>
    <td>Numero de téléphone
    </td>
<td>
        {{ @$producteur->phone1 }} 
    </td>
</tr>

<tr>
    <td>Avez-vous un proche à contacter pour vous joindre
    </td>
<td>
        {{ @$producteur->autreMembre }} </td>
</tr> 
    <tr>
        <td>
        </td>
<td>
            {{ @$producteur->autrePhone }} 
        </td>
</tr>
    <tr>
        <td>Numero de téléphone
        </td>
<td>
            {{ @$producteur->phone2 }} 
        
    </td>
</tr>
<tr>
   <td> Niveau d'étude 
    </td>
<td>
        {{ @$producteur->niveau_etude }} 
    </td>
</tr>
<tr>
    <td>Type de pièces
    </td>
<td>
        {{ @$producteur->type_piece }} 
    </td>
</tr>

<tr>
    <td>N° de la pièce
    </td>
<td>
        {{ @$producteur->numPiece }} </td>
</tr>
         

<tr>
    <td>N° de carte CCC
    </td>
<td>
        {{ @$producteur->num_ccc }} </td>
</tr>
                
<tr>
    <td>Avez-vous une carte CMU ?
    </td>
<td>
        {{ @$producteur->carteCMU }} </td>
</tr>
            
    <tr>
        <td>N° de la pièce CMU
        </td>
<td>
            {{ @$producteur->numCMU }}  </td>
</tr>

<tr>
    <td>Votre type de carte de sécurité social
    </td>
<td>
        {{ @$producteur->typeCarteSecuriteSociale }} 
    </td>
</tr> 
    <tr>
        <td>N° de carte de sécurité sociale
        </td>
<td>

            {{ @$producteur->numSecuriteSociale }} 
        
    </td>
</tr>
 
</table>
</body>
</html>