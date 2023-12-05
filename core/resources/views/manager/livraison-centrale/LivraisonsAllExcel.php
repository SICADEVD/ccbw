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
        <td>Nom</td>
        <td>Prenoms</td>
        <td>Code Prod</td>
        <td>code Prod app</td> 
        <td>Sexe</td>
        <td>Date Naiss</td>
        <td>Phone 1</td>
        <td>Phone 2</td>
        <td>Nationalite</td>
        <td>Type piece</td>
        <td>Numero Piece</td>
        <td>Niveau Etude</td>
        <td>Status</td>
        <td>Certificat</td>
        <td>Date enreg</td> 
    </tr>
    </thead> 
    <?php
    foreach($livraisons as $c)
    {
    ?>
        <tbody>
        <tr>
            <td><?php echo $c->id; ?></td>
            <td><?php echo $c->localite->nom; ?></td>
            <td><?php echo $c->nom; ?></td>
            <td><?php echo $c->prenoms; ?></td>
            <td><?php echo $c->codeProd; ?></td>
            <td><?php echo $c->codeProdapp; ?></td>
            <td><?php echo $c->sexe; ?></td>
            <td><?php echo date('d-m-Y', strtotime($c->dateNaiss)); ?></td>
            <td><?php echo $c->phone1; ?></td>
            <td><?php echo $c->phone2; ?></td>
            <td><?php echo $c->nationalite; ?></td>
            <td><?php echo $c->type_piece; ?></td>
            <td><?php echo $c->numPiece; ?></td>
            <td><?php echo $c->niveau_etude; ?></td>
            <td><?php echo $c->statut; ?></td>
            <td><?php echo $c->certificat; ?></td>
            <td><?php echo date('d-m-Y', strtotime($c->created_at)); ?></td>
        </tr>
        </tbody>
        <?php
    }
    ?>

</table>