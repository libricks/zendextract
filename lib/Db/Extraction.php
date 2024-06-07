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

namespace OCA\ZendExtract\Db;

use OCP\AppFramework\Db\Entity;

class Extraction extends Entity
{

    protected $name;
    protected $defaultPath;
    protected $brandId;
    protected $groupId;
    public function __construct() {
        // add types in constructor
//       $this->addType('name', 'string');
//        $this->addType('defaultpath', 'string');
    }
}
