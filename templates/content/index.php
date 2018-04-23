<h1>Extractions</h1>
<div class="form-group ">
    <label class="col-sm-1 control-label" for="forms" style="margin-bottom: 25px">Groupe</label>
    <div class="col-sm-6">
        <select required id="group" data-selected-text-format="count" name="group" class="selectpicker"
                title="Sélectionnez le groupe auquel attribuer cette extraction" >
            <option></option>
            <?php foreach ($_['group'] as $group): ?>
                <option title="<?php echo $group->getGid() ?>" value="<?php echo $group->getGid()?>"><?php echo $group->getGid() ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<table class="table table-bordered table-striped table-hover" id="myTable">
    <tr>
        <th>Nom</th>
        <th>Options</th>

    </tr>

    <?php foreach($_["extractions"] as $extraction): ?>
        <tr data-group-id="<?php echo $extraction->getGroupId(); ?>">
            <td><?php echo $extraction->getName() ?></td>

            <td>
                <a  class="btn btn-warning" href="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/extraction/step/1/<?php echo $extraction->id ?>">
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