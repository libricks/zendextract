<?php

/**
 * nextCloud - Zendesk Xtractor
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Tawfiq CADI TAZI <tawfiq@caditazi.fr>
 * @copyright Marc-Henri Pamiseux 2017
 */


namespace OCA\ZendExtract\Service;

use \OCP\IConfig;

class ZendDeskAPI
{


    private $subdomain;
    private $username;
    private $token;
    private $uri;

    public function __construct($AppName, IConfig $config)
    {

        $this->subdomain = $config->getAppValue($AppName, 'zendextract_domain');
        $this->username = $config->getAppValue($AppName, 'zendextract_email');
        $this->token = $config->getAppValue($AppName, 'zendextract_token');

        $this->uri = "https://$this->subdomain.zendesk.com";


    }

    public function get($endpoint, $params)
    {

        try {

            $response = \Httpful\Request::get($this->uri . $endpoint)
                ->sendsJson()
                ->authenticateWith($this->username . "/token", $this->token)
                ->send();
            $result = $response->body;
            return $result;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getAbsolute($uri)
    {

        try {

            $response = \Httpful\Request::get($uri)
                ->sendsJson()
                ->authenticateWith($this->username . "/token", $this->token)
                ->send();
            $result = $response->body;
            return $result;
        } catch (Exception $e) {
            return null;
        }
    }

}