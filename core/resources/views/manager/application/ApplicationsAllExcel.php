<style>
    #categories {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #categories td, #categories th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #categories tr:nth-child(even){background-color: #f2f2f2;}

    #categories tr:hover {background-color: #ddd;}

    #categories th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
</style>

<table id="categories" width="100%">
    <thead>
    <tr>
        <td>ID</td> 
        <td>Campagne</td> 
        <td>Cooperative</td>
        <td>Section</td>
        <td>Localite</td>
        <td>Nom</td>
        <td>Prenoms</td>
        <td>Code Producteur</td>
        <td>Code Parcelle</td> 
        <td>Applicateur</td>
        <td>Superficie Pulverisee</td>
<td>delais Reentree</td>
<td>personne Application</td>
<td>suivi Formation</td>
<td>attestion</td>
<td>bilanSante</td>
<td>independant Epi</td>
<td>etat Epi</td>  
<td>Date Application</td>
<td>Heure Application</td>
    </tr>
    </thead> 
    <?php
    foreach($applications as $c)
    {
    ?>
        <tbody>
        <tr>
            <td><?php echo $c->id; ?></td> 
            <td><?php echo $c->campagne->nom; ?></td>
            <td><?php echo $c->parcelle->producteur->localite->section->cooperative->name; ?></td>
            <td><?php echo $c->parcelle->producteur->localite->section->libelle; ?></td>
            <td><?php echo $c->parcelle->producteur->localite->nom; ?></td>
            <td><?php echo $c->parcelle->producteur->nom; ?></td> 
            <td><?php echo $c->parcelle->producteur->prenoms; ?></td> 
            <td><?php echo $c->parcelle->producteur->codeProd; ?></td>  
            <td><?php echo $c->parcelle->codeParc; ?></td>
            <td><?php echo $c->user->lastname; ?> <?php echo $c->user->firstname; ?></td>

            <td><?php echo $c->superficiePulverisee; ?></td>
<td><?php echo $c->delaisReentree; ?></td>
<td><?php echo $c->personneApplication; ?></td>
<td><?php echo $c->suiviFormation; ?></td>
<td><?php echo $c->attestion; ?></td>
<td><?php echo $c->bilanSante; ?></td>
<td><?php echo $c->independantEpi; ?></td>
<td><?php echo $c->etatEpi; ?></td>  
<td><?php echo $c->date_application; ?></td>
<td><?php echo $c->heure_application; ?></td>
        </tr>
        </tbody>
        <?php
    }
    ?>

</table>