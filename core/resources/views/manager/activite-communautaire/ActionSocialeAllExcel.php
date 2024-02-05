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
        <td>Localite</td>
        <td>Campagne</td>
        <td>Nom</td>
        <td>Prenoms</td>
        <td>Code Prod</td>
        <td>Parcelle</td> 
        <td>Applicateur</td>

        <td>Superficie Pulverisee</td>
<td>Marque Produit Pulverise</td>
<td>Matieres Actives</td>
<td>Degre Dangerosite</td>
<td>Raison Application</td>
<td>Nom Insectes Cibles</td>
<td>Delais Reentree</td>
<td>Zone Tampons</td> 
<td>Presence Douche</td> 
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
            <td><?php echo $c->parcelle->producteur->localite->nom; ?></td>
            <td><?php echo $c->campagne->nom; ?></td>
            <td><?php echo $c->parcelle->producteur->nom; ?></td>
            <td><?php echo $c->parcelle->producteur->prenoms; ?></td>
            <td><?php echo $c->parcelle->producteur->codeProd; ?></td>
            <td><?php echo $c->parcelle->codeParc; ?></td>
            <td><?php echo $c->user->lastname; ?> <?php echo $c->user->firstname; ?></td>

            <td><?php echo $c->superficiePulverisee; ?></td>
<td><?php echo $c->marqueProduitPulverise; ?></td>
<td><?php echo $c->matieresActives; ?></td>
<td><?php echo $c->degreDangerosite; ?></td>
<td><?php echo $c->raisonApplication; ?></td>
<td><?php echo $c->nomInsectesCibles; ?></td>
<td><?php echo $c->delaisReentree; ?></td>
<td><?php echo $c->zoneTampons; ?></td> 
<td><?php echo $c->presenceDouche; ?></td> 
<td><?php echo $c->date_application; ?></td>
<td><?php echo $c->heure_application; ?></td>
        </tr>
        </tbody>
        <?php
    }
    ?>

</table>