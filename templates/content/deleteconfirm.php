
<h1>Extractions</h1>

<h2>Confirmation de suppression</h2>
<form method="post"  action="/index.php/<?php echo $_["webRoot"]."/apps/zendextract/extraction/deleteConfirm/". $_["extraction"]->id ?>">
    <p>Etes-vous sûr de vouloir supprimer l'extraction :<b> <?php echo $_["extraction"]->getName() ?></b></p>
    <input type="submit" value="Confirmer" class="btn btn-danger"/>
    <a href="<?php echo $_["webRoot"]."/apps/zendextract/"?>" class="btn btn-warning">Annuler</a>
</form>

