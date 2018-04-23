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

namespace OCA\ZendExtract\Controller;

use \OCP\IConfig;
use \OCP\IRequest;
use \OCP\AppFramework\Http\TemplateResponse;
use \OCP\AppFramework\Controller;
use \OCP\AppFramework\Http\JSONResponse;
use \OCP\AppFramework\Http;


class SettingsController extends Controller {

    private $userId;
    private $mapper;
    private $cityMapper;
    private $config;



    public function __construct ($appName, IConfig $config, IRequest $request, $userId) {
        parent::__construct($appName, $request);
        $this->userId = $userId;
        $this->config = $config;
    }

    public function apiKeySet ($domain, $email, $token) {
        $this->config->setAppValue($this->appName, 'zendextract_domain', $domain);
        $this->config->setAppValue($this->appName, 'zendextract_email', $email);
        $this->config->setAppValue($this->appName, 'zendextract_token', $token);

        return new JSONResponse(array(
            "zendextract_domain" => $this->config->getAppValue($this->appName, 'zendextract_domain'),
            "zendextract_email" => $this->config->getAppValue($this->appName, 'zendextract_email'),
            "zendextract_token" => $this->config->getAppValue($this->appName, 'zendextract_token')
        ));
    }

};
?>
