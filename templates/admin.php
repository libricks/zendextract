<?php
\OCP\Util::addScript('zendextract', 'admin');

?>
<div id="zendextract">
    <div class="section">
        <h2>Zend Extract</h2>
        <div class="form-line">
            <p>
                <label for="zendextract_domain">Domaine</label></p>

            <p>
                <em>Votre domaine ZendDesk</em>
            </p>
            <p><input type="text" id="zendextract_domain" name="zendextract_domain" value="<?php p($_['zendextract_domain']) ?>"/></p>
        </div>

        <div class="form-line">
            <p>
                <label for="zendextract_email">Email</label></p>

            <p>
                <em>Votre email d'identification à l'API ZendDesk</em>
            </p>
            <p><input type="text" id="zendextract_email" name="zendextract_email" value="<?php p($_['zendextract_email']) ?>"/></p>
        </div>

        <div class="form-line">
            <p>
                <label for="zendextract_token">Token</label></p>

            <p>
                <em>Votre token d'identification à l'API ZendDesk</em>
            </p>
            <p><input type="text" id="zendextract_token" name="zendextract_token" value="<?php p($_['zendextract_token']) ?>"/></p>
        </div>
        <input type="submit" id="submitOWMApiKey" value="<?php p($l->t('Save')); ?>"/>
    </div>
</div>