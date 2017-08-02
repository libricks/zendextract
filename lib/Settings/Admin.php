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

namespace OCA\ZendExtract\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

use OCP\IConfig;

class Admin implements ISettings
{

    /** @var IConfig */
    private $config;

    /**
     * @param IL10N $l10n
     */
    public function __construct(IConfig $config)
    {
        $this->config = $config;
    }


    /**
     * @return TemplateResponse
     */
    public function getForm()
    {


        $zendextract_email = $this->config->getAppValue('zendextract', 'zendextract_email');

        $zendextract_token = $this->config->getAppValue('zendextract', 'zendextract_token');
        $zendextract_domain = $this->config->getAppValue('zendextract', 'zendextract_domain');

        return new TemplateResponse('zendextract',
            'admin',
            ['appId' => 'zendextract',
                'zendextract_domain' => $zendextract_domain,
                'zendextract_email' => $zendextract_email,
                'zendextract_token' => $zendextract_token],
            '');
    }

    /**
     * @return string the section ID, e.g. 'sharing'
     */
    public function getSection()
    {
        return 'additional';
    }

    /**
     * @return int whether the form should be rather on the top or bottom of
     * the admin section. The forms are arranged in ascending order of the
     * priority values. It is required to return a value between 0 and 100.
     *
     * E.g.: 70
     */
    public function getPriority()
    {
        return 90;
    }

}