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
        <td>Section</td>
        <td>Localite</td>
        <td>Nom</td>
        <td>Prenoms</td>
        <td>Code Prod</td>
        <td>Activite</td>
    </tr>
    </thead>
    <?php
    foreach($autresactivites as $c)
    {
    ?>

        <tbody>

        <tr>
            <td><?php echo $c->id; ?></td>
            <td><?php echo $c->producteurInfo->localite->section->cooperative->name  ?? ""; ?></td>
            <td><?php echo $c->producteurInfo->localite->section->libelle  ?? ""; ?></td>
            <td><?php echo $c->producteurInfo->localite->nom  ?? ""; ?></td>
            <td><?php echo $c->producteurInfo->nom  ?? "" ; ?></td>
            <td><?php echo $c->producteurInfo->prenoms  ?? "" ; ?></td>
            <td><?php echo $c->producteurInfo->codeProd  ?? "" ; ?></td>
            <td><?php echo $c->typeactivite  ?? "" ; ?></td>
        </tr>
        </tbody>
        <?php
    }
    ?>

</table>