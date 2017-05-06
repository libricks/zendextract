<?php
/**
 * nextCloud - Zendesk Xtractor
 *
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\ZendeXtract\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 *
 * @author Marc-Henri Pamiseux <mhp@libricks.org>
 * @copyright Marc-Henri Pamiseux 2017
 * @license AGPL
 * @license https://opensource.org/licenses/AGPL-3.0
 */

namespace OCA\ZendeXtract\AppInfo;

return [
    'routes' => [
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
	   ['name' => 'page#do_echo', 'url' => '/echo', 'verb' => 'POST'],
    ]
];
