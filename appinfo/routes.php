<?php
/**
 * nextCloud - Zendesk Xtractor
 *
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\ZendExtract\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 *
 * @author Marc-Henri Pamiseux <mhp@libricks.org>
 * @copyright Marc-Henri Pamiseux 2017
 * @license AGPL
 * @license https://opensource.org/licenses/AGPL-3.0
 */

namespace OCA\ZendExtract\AppInfo;

return [
    'routes' => [
        ['name' => 'extraction#index', 'url' => '/', 'verb' => 'GET'],
        ['name' => 'extraction#create', 'url' => '/extraction/create', 'verb' => 'GET'],
        ['name' => 'extraction#delete', 'url' => '/extraction/delete/{id}', 'verb' => 'GET'],
        ['name' => 'extraction#deleteConfirm', 'url' => '/extraction/deleteConfirm/{id}', 'verb' => 'POST'],
        ['name' => 'extraction#step1POST', 'url' => '/extraction/step1POST', 'verb' => 'POST'],
        ['name' => 'extraction#step2POST', 'url' => '/extraction/step2POST', 'verb' => 'POST'],
        ['name' => 'extraction#step3POST', 'url' => '/extraction/step3POST', 'verb' => 'POST'],
        ['name' => 'extraction#generate', 'url' => '/extraction/generate', 'verb' => 'POST'],
        ['name' => 'extraction#step', 'url' => '/extraction/step/{step}/{id}', 'verb' => 'GET'],
        ['name' => 'extraction#export', 'url' => '/export', 'verb' => 'GET'],
        ['name' => 'settings#apikeyset',	'url' => '/settings/apikey',	'verb' => 'POST']
    ]
];
