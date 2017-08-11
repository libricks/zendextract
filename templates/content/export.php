<h1>Exports</h1>

<form class="" method="post" action="<?php echo $_["webRoot"] ?>/index.php/apps/zendextract/extraction/generate">
    <div class="form-group">
        <label for="extractions">Extraction</label>
        <select required id="extractions" name="extractionId" class="selectpicker"
                title="Sélectionnez une extraction">

            <?php foreach ($_['extractions'] as $extraction): ?>

                <option title="<?php echo $extraction->getName() ?>" <?php echo $extraction->id == $_["extractionId"] ? "selected" : "" ?>
                        value="<?php echo $extraction->id ?>"><?php echo $extraction->getName() ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <h3>Date de traitement</h3>
    <div class="form-group">
        <label for="datetimepicker1">De</label>
        <input name="fromTreatment" type='text' class="form-control" id="datetimepicker1"/>
    </div>
    <div class="form-group">
        <label for="datetimepicker2">A</label>
        <input name="toTreatment" type='text' class="form-control" id="datetimepicker2"/>
    </div>
    <h3>Date de contact</h3>
    <div class="form-group">
        <label for="datetimepicker3">De</label>
        <input name="fromContact" type='text' class="form-control" id="datetimepicker3"/>
    </div>
    <div class="form-group">
        <label for="datetimepicker4">A</label>
        <input name="toContact" type='text' class="form-control" id="datetimepicker4"/>
    </div>

    <div class="form-group">
        <label for="type">Type</label>
        <select id="type" data-selected-text-format="count" name="type" class="selectpicker"
                title="Sélectionner un type">
            <option value="0"></option>
            <option value="1">Information</option>
            <option value="2">Réclamation</option>
        </select>
    </div>

    <div style="margin-top: 20px;">
        <div>
            <span class="message" style="color: red"></span>
        </div>
        <button type="submit" class="btn btn-success hold-submit">Exporter</button>
        <div style="display: none;">
            <img style="width: 50px" src="<?php print_unescaped(image_path('zendextract', 'spinner.gif')); ?>"/>
            Veuillez patienter pendant la création de votre fichier CSV. En fonction du nombre de résultats, cela peut
            durer plusieurs minutes
        </div>
    </div>
</form>


