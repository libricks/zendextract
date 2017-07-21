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

namespace OCA\ZendExtract\Db;

use OCP\AppFramework\Db\Entity;

class Form extends Entity
{

    protected $name;
    protected $formId;
    protected $displayName;
    protected $extractionId;

    public function __construct() {
        // add types in constructor
//       $this->addType('name', 'string');
//        $this->addType('defaultpath', 'string');
    }
}