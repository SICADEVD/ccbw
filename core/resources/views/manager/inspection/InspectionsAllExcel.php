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
        <td>Type inspection</td>
        <td>Localite</td>
        <td>Campagne</td>
        <td>Nom</td>
        <td>Prenoms</td>
        <td>Code Prod</td>
        <td>Encadreur</td> 
        <td>Note</td>
        <td>Date Inspection</td> 
    </tr>
    </thead> 
    <?php
    foreach($inspections as $c)
    {
    ?>
        <tbody>
        <tr>
            <td><?php echo $c->id; ?></td>
            <td><?php echo $c->certificat; ?></td>
            <td><?php echo $c->producteur->localite->nom; ?></td>
            <td><?php echo $c->campagne->nom; ?></td>
            <td><?php echo $c->producteur->nom; ?></td>
            <td><?php echo $c->producteur->prenoms; ?></td>
            <td><?php echo $c->producteur->codeProd; ?></td>
            <td><?php echo $c->user->lastname; ?> <?php echo $c->user->firstname; ?></td>
            <td><?php echo $c->note; ?></td> 
            <td><?php echo $c->date_evaluation; ?></td>
        </tr>
        </tbody>
        <?php
    }
    ?>

</table>