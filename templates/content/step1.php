<h1>Etape 1 - Extraction</h1>
<h2></h2>

<form class="form-horizontal" method="post"
      action="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/extraction/step1POST">
    <input type="hidden" name="id" value="<?php echo $_["extraction"]->id ?>"/>
    <input type="hidden" name="mode" value="<?php echo $_["mode"] ?>"/>
    <div class="form-group ">
        <label class="col-sm-2 control-label" for="name">Nom</label>
        <div class="col-sm-6">
            <input required name="name" type="text" class="form-control" id="name" placeholder="Nom de l'extraction"
                   value="<?php echo $_["extraction"]->getName() ?>">
        </div>
    </div>
    <!--    <div class="form-group ">-->
    <!--        <label class="col-sm-2 control-label" for="folder">Dossier d'extraction par défaut</label>-->
    <!--        <div class="col-sm-6">-->
    <!--            <input required name="defaultpath" type="text" class="form-control" id="folder" placeholder="Dossier d'extraction par défaut">-->
    <!--        </div>-->
    <!--    </div>-->


    <div class="form-group ">
        <label class="col-sm-2 control-label" for="forms">Marque</label>
        <div class="col-sm-2">
            <select id="brand-selection" data-selected-text-format="count" name="brand_id" class="selectpicker"
                    title="Sélectionnez une marque">
                <option></option>
                <?php foreach ($_['brands'] as $brand): ?>

                    <option title="<?php echo $brand->getName() ?>"
                            value="<?php echo $brand->getId() ?>"
                        <?php echo ($brand->getId() ==  $_["extraction"]->getBrandId()) ? "selected" : "" ?>
                    >
                        <?php echo $brand->getName() ?>

                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <label class="col-sm-2 control-label" for="forms">Créer une marque</label>
        <div class="col-sm-2">
            <input id="newbrand" name="newbrand" type="text" class="form-control" id="folder" placeholder="Associer une nouvelle marque">
        </div>
    </div>

    <?php if ($_["mode"] == "create") : ?>
        <div class="form-group ">
            <label class="col-sm-2 control-label" for="forms">Formulaires</label>
            <div class="col-sm-6">
                <select required id="forms" data-selected-text-format="count" name="forms[]" class="selectpicker"
                        multiple
                        title="Sélectionnez les formulaires">

                    <?php foreach ($_['forms']->ticket_forms as $form): ?>
                        <option title="<?php echo $form->name ?>" value="<?php echo $form->id ?>"><?php echo $form->name ?> - <?php echo $form->display_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>
        <div class="form-group ">
            <label class="col-sm-2 control-label" for="forms">Groupe</label>
            <div class="col-sm-6">
                <select required id="group" data-selected-text-format="count" name="group" class="selectpicker"
                        title="Sélectionnez le groupe auquel attribuer cette extraction">
                    <?php foreach ($_['groups'] as $group): ?>
                        <option title="<?php echo $group->getGid() ?>" value="<?php echo $group->getGid()?>"><?php echo $group->getGid() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>





    <div class="form-group ">

        <div class="col-sm-6 col-sm-offset-2">
            <button type="submit" class="btn btn-success hold-submit">Suivant</button>
            <div style="display: none;">
                <img style="width: 50px" src="<?php print_unescaped(image_path('zendextract', 'spinner.gif')); ?>"/>
                Veuillez patienter pendant la création de votre extraction. En fonction du nombre de formulaires
                sélectionnés, cela peut éventuellement durer plusieurs minutes.
            </div>
        </div>
    </div>


</form>
