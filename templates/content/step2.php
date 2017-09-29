<h1 id="step2">Etape 2 - Sélection des champs</h1>
<h2>Extraction : <?php echo $_["extraction"]->getName() ?> </h2>
<form class="form-horizontal" method="post"
      action="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/extraction/step2POST">
    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th>Formulaire</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Nom de la colonne</th>
        </tr>

        <?php foreach ($_["fields"] as $field): ?>
            <tr>
                <td><?php echo $field->getFormName() ?></td>
                <td><?php echo $field->getTitle() ?></td>
                <td><?php echo $field->getType() ?></td>
                <td><input type="checkbox" name="selected_fields[]"
                           value="<?php echo $field->id; ?>" <?php echo $field->getIsActive() ? "checked" : "" ?>/></td>
            </tr>
        <?php endforeach; ?>
    </table>


    <input type="hidden" name="id" value="<?php echo $_["extraction"]->id ?>"/>
    <a href="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/extraction/step/1/<?php echo $_["extraction"]->id ?>"
       class="btn btn-success">Précédent</a>
    <button type="submit" class="btn btn-success">Suivant</button>

</form>

<form class="form-horizontal" method="post" style="margin-top: 10px;margin-bottom: 10px;"
      action="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/extraction/step2UPDATE">

    <input type="hidden" name="id" value="<?php echo $_["extraction"]->id ?>"/>
    <button type="submit" class="btn btn-success">Mettre à jour les champs</button>


</form>