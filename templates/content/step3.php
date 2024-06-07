<h1 id="step3">Etape 3- Configuration des champs</h1>
<h2>Extraction : <?php echo $_["extraction"]->getName() ?> </h2>
<form class="form-horizontal" method="post"
      action="<?php echo $_["webRoot"] ?>/apps/zendextract/extraction/step3POST">
    <table class="table table-bordered table-striped table-hover sortable">
        <tr>
            <th>Formulaire</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Nom de la colonne</th>
            <th>Option de personnalisation</th>
            <th></th>
            <th>Fusion</th>
            <th>Ordre</th>

        </tr>

        <?php foreach ($_["fields"] as $field): ?>
            <tr>
                <td><?php echo $field->getFormName() ?></td>
                <td><?php echo $field->getTitle() ?></td>
                <td><?php echo $field->getType() ?></td>
                <td>
                    <input name="fields[<?php echo $field->id ?>][column_name]" class="form-control" type="text"
                           value="<?php echo $field->getColumnName() ?>"/>
                    <input name="fields[<?php echo $field->id ?>][id]" type="hidden" value="<?php echo $field->id ?>"/>
                </td>
                <td>
                    <select name="fields[<?php echo $field->id ?>][custom_field_type]" class="selectpicker options">


                        <option <?php echo $field->getCustomFieldType() == "0" ? "selected" : "" ?> value="0"></option>
                        <option <?php echo $field->getCustomFieldType() == "1" ? "selected" : "" ?> value="1">Formater
                            une date
                        </option>
                        <option <?php echo $field->getCustomFieldType() == "2" ? "selected" : "" ?> value="2">Scinder
                            colonne famille
                        </option>
                        <option <?php echo $field->getCustomFieldType() == "3" ? "selected" : "" ?> value="3">Texte
                            fixe
                        </option>
                        <option <?php echo $field->getCustomFieldType() == "4" ? "selected" : "" ?> value="4">oui/vide</option>
                    </select>
                </td>
                <!--                <td></td>-->
                <td>
                    <div data-option="1">
                        <label>Format de la date</label>
                        <input name="fields[<?php echo $field->id ?>][date_format]" class="form-control" type="text"
                               value="<?php echo $field->getDateFormat() ?>"/>
                        <a href="https://www.php.net/manual/fr/datetime.format.php" target="_blank">format</a>
                    </div>
                    <div data-option="2">
                        <label>Nombre de colonnes</label>
                        <input name="fields[<?php echo $field->id ?>][nb_columns]" type="number" min="0" max="20"
                               value="<?php echo $field->getNbColumns() ?>">
                        <br>
                        <label>Noms des colonnes</label>
                        <input name="fields[<?php echo $field->id ?>][columns_names]" class="form-control" type="text"
                               value="<?php echo $field->getColumnsNames() ?>"/>
                    </div>
                    <div data-option="3">
                        <label>Texte</label>
                        <input name="fields[<?php echo $field->id ?>][custom_text]" type="text"
                               value="<?php echo $field->getCustomText() ?>">

                    </div>

                </td>
                <td>
                    <input type="checkbox" name="fields[<?php echo $field->id ?>][is_merged]" <?php echo ($field->getIsMerged() ? "checked='checked'" : "" )?>/>
                    <input type="text" name="fields[<?php echo $field->id ?>][merge_name]" value="<?php echo $field->getMergeName() ?>"/>
                </td>
                <td>


                    <button type="button" class="move up btn btn-default"><i class="fa fa-chevron-circle-up"
                                                                             aria-hidden="true"></i></button>
                    <button type="button" class="move down btn btn-default"><i class="fa fa-chevron-circle-down"
                                                                               aria-hidden="true"></i></button>
                </td>


            </tr>

        <?php endforeach; ?>
    </table>


    <input type="hidden" name="id" value="<?php echo $_["extraction"]->id ?>"/>
    <a href="<?php echo $_["webRoot"] ?>/apps/zendextract/extraction/step/2/<?php echo $_["extraction"]->id ?>"
       class="btn btn-success">Précédent</a>
    <button type="submit" class="btn btn-success">Sauvegarder</button>


</form>
