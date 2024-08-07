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
        <td>Cooperative</td>
        <td>Campagne</td>
        <td>Periode</td>
        <td>Section</td>
        <td>Localite</td>
        <td>Nom</td>
        <td>Prenoms</td>
        <td>Code Producteur</td>
        <td>Code Parcelle</td>

        <td>type produit</td> 
        <td>certificat</td> 
        <td>Prix achats</td>
        <td>Cout total</td>
        <td>Quantite</td>
        <td>Quantite sprtante</td>
        <td>Quantite restante</td> 
        <td>Date enreg</td> 
    </tr>
    </thead> 
    <?php
    foreach($producteurs as $c)
    {
    ?>
        <tbody>
        <tr>
            <td><?php echo $c->id; ?></td>
            <td><?php echo $c->livraisonInfo->senderCooperative->name; ?></td>
            <td><?php echo $c->campagne->nom; ?></td>
            <td><?php echo $c->campagnePeriode->nom; ?></td>
            <td><?php echo $c->parcelle->producteur->localite->section->libelle; ?></td>
            <td><?php echo $c->parcelle->producteur->localite->nom; ?></td>
            <td><?php echo $c->parcelle->producteur->nom; ?></td> 
            <td><?php echo $c->parcelle->producteur->prenoms; ?></td> 
            <td><?php echo $c->parcelle->producteur->codeProd; ?></td>  
            <td><?php echo $c->parcelle->codeParc; ?></td>

            <td><?php echo $c->type_produit; ?></td>
            <td><?php echo $c->certificat; ?></td> 
            <td><?php echo $c->type_price; ?></td>
            <td><?php echo $c->fee; ?></td>
            <td><?php echo $c->qty; ?></td>
            <td><?php echo $c->qty_sortant; ?></td>
            <td><?php echo $c->qty - $c->qty_sortant; ?></td> 
            <td><?php echo date('d-m-Y', strtotime($c->created_at)); ?></td>
        </tr>
        </tbody>
        <?php
    }
    ?>

</table>