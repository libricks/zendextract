<?php
/**
 * nextCloud - Zendesk Xtractor
 *
 * This file is licensed under the GNU Affero General Public License version 3
 * or later. See the COPYING file.
 *
 * @author Tawfiq Cadi Tazi <tawfiq@caditazi.fr>
 * @copyright Copyright (C) 2017 SARL LIBRICKS
 * @license AGPL
 * @license https://opensource.org/licenses/AGPL-3.0
 */

namespace OCA\ZendExtract\Service;

use \OCP\IConfig;

require_once __DIR__ . '/../../vendor/autoload.php';
use Zendesk\API\HttpClient as ZendeskAP;
class ZendDeskAPI
{


    private $subdomain;
    private $username;
    private $token;
    private $client;

    public function __construct($AppName, IConfig $config)
    {

        $this->subdomain = $config->getAppValue($AppName, 'zendextract_domain');
        $this->username = $config->getAppValue($AppName, 'zendextract_email');
        $this->token = $config->getAppValue($AppName, 'zendextract_token');
        // TODO : Check how to put this URI in a variable.

        $this->client = new ZendeskAP($this->subdomain);
        $this->client->setAuth('basic', ['username' => $this->username, 'token' => $this->token]);

    }

    public function get($endpoint, $params)
    {
        $response = "";
        try {

          //  $response = \Httpful\Request::get($this->uri . $endpoint)
          //      ->sendsJson()
          //      ->authenticateWith($this->username . "/token", $this->token)
          //      ->send();
            //          $result = $response->body;



      //      $result = $this->client->tickets()->findAll();


            $result = $this->client->get($endpoint);

            return $result;
        } catch (Httpful\Exception $e) {
                $this->logger->error("Problème lors de la récupération des tickets " . $e->getMessage(), array('app' => $this->appName));

        } catch (Exception $e) {
            $this->logger->error("Problème API : " . $e->getMessage() . " ---------" . $response, array('app' => $this->appName));
        }
    }

    public function getAbsolute($uri)
    {

        try {

           /* $response = \Httpful\Request::get($uri)
                ->sendsJson()
                ->authenticateWith($this->username . "/token", $this->token)
                ->send();
            $result = $response->body;*/
            $result = $this->client->tickets()->find($uri);
            return $result;
        } catch (Exception $e) {
            return null;
        }
    }

}
