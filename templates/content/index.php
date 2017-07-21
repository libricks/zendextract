<h1>Extractions</h1>

<table class="table table-bordered table-striped table-hover">
    <tr>
        <th>Nom</th>
        <th>Options</th>

    </tr>

    <?php foreach($_["extractions"] as $extraction): ?>
        <tr>
            <td><?php echo $extraction->getName() ?></td>

            <td>
                <a  class="btn btn-warning" href="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/extraction/step/2/<?php echo $extraction->id ?>">
                    <i class="fa fa-pencil" aria-hidden="true"></i>  Modifier
                </a>
                <a class="btn btn-info" href="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/export?id=<?php echo $extraction->id ?>">
                    <i class="fa fa-file-text" aria-hidden="true"></i>   Exporter
                </a>
                <a class="btn btn-danger" href="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/extraction/delete/<?php echo $extraction->id ?>">
                    <i class="fa fa-trash" aria-hidden="true"></i>   Supprimer
                </a>
            </td>

        </tr>
    <?php endforeach; ?>
</table>

<a href="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/extraction/create" class="btn btn-success">Créer une nouvelle extraction</a>