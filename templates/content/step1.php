<h1>Etape 1 - Extraction</h1>
<h2></h2>

<form class="form-horizontal" method="post" action="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/extraction/step1POST">
    <input type="hidden" name="id" value="<?php echo $_["extraction"]->id ?>"/>
    <div class="form-group ">
        <label class="col-sm-2 control-label" for="name">Nom</label>
        <div class="col-sm-6">
            <input required name="name" type="text" class="form-control" id="name" placeholder="Nom de l'extraction" value="<?php echo $_["extraction"]->getName() ?>">
        </div>
    </div>
<!--    <div class="form-group ">-->
<!--        <label class="col-sm-2 control-label" for="folder">Dossier d'extraction par défaut</label>-->
<!--        <div class="col-sm-6">-->
<!--            <input required name="defaultpath" type="text" class="form-control" id="folder" placeholder="Dossier d'extraction par défaut">-->
<!--        </div>-->
<!--    </div>-->

    <div class="form-group ">
        <label class="col-sm-2 control-label" for="forms">Formulaires</label>
        <div class="col-sm-6">
            <select required id="forms" data-selected-text-format="count" name="forms[]" class="selectpicker" multiple
                    title="Sélectionnez les formulaires">

                <?php foreach ($_['forms']->ticket_forms as $form): ?>

                    <option title="<?php echo $form->name ?>" <?php echo in_array($form->id, $_["selected_forms_ids"]) ? "selected" : "" ?>
                            value="<?php echo $form->id ?>"><?php echo $form->name ?> - <?php echo $form->display_name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group ">

        <div class="col-sm-6 col-sm-offset-2">
            <button type="submit" class="btn btn-success hold-submit">Suivant</button>
            <div style="display: none;">
                <img style="width: 50px" src="<?php print_unescaped(image_path('zendextract', 'spinner.gif'));?>"/>
                Veuillez patienter pendant la création de votre extraction. En fonction du nombre de formulaires sélectionnés, cela peut éventuellement durer plusieurs minutes.
            </div>
        </div>
    </div>


</form>
