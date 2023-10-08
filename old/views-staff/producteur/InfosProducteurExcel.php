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
        <td>Nom</td>
        <td>Prenoms</td>
        <td>Code Prod</td>
        <td>foretsjachere</td>
<td>Superficie</td>
<td>Autres Cultures</td>
<td>Age 18 ans</td>
<td>Personne scoalisee</td>
<td>Scolarises Extrait</td>
<td>Travailleurs</td>
<td>Travailleurs Permanents</td>
<td>Travailleurs Temporaires</td>
<td>Personne Blessee</td>
<td>Type Documents</td>
<td>Recu Achat</td>
<td>Mobile Money</td>
<td>Operateur Mobile Money</td>
<td>Numero Compte Mobile Money</td>
<td>Paiement Mobile Money</td>
<td>Compte Banque</td>

    </tr>
    </thead> 
    <?php
    foreach($infos as $c)
    {
    ?>
        <tbody>
        <tr>
            <td><?php echo $c->id; ?></td> 
            <td><?php echo $c->producteur->nom; ?></td> 
            <td><?php echo $c->producteur->prenoms; ?></td> 
            <td><?php echo $c->producteur->codeProd; ?></td> 
            <td><?php echo $c->foretsjachere; ?></td>
<td><?php echo $c->superficie; ?></td>
<td><?php echo $c->autresCultures; ?></td>
<td><?php echo $c->age18; ?></td>
<td><?php echo $c->persEcole; ?></td>
<td><?php echo $c->scolarisesExtrait; ?></td>
<td><?php echo $c->travailleurs; ?></td>
<td><?php echo $c->travailleurspermanents; ?></td>
<td><?php echo $c->travailleurstemporaires; ?></td>
<td><?php echo $c->personneBlessee; ?></td>
<td><?php echo $c->typeDocuments; ?></td>
<td><?php echo $c->recuAchat; ?></td>
<td><?php echo $c->mobileMoney; ?></td>
<td><?php echo $c->operateurMM; ?></td>
<td><?php echo $c->numeroCompteMM; ?></td>
<td><?php echo $c->paiementMM; ?></td>
<td><?php echo $c->compteBanque; ?></td>
        </tr>
        </tbody>
        <?php
    }
    ?>

</table>