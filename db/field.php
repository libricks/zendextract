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

class Field extends Entity
{

    protected $formId;
    protected $fieldId;
    protected $orderIndex;
    protected $title;
    protected $type;

    protected $columnName;
    protected $customFieldType;
    protected $dateFormat;
    protected $nbColumns;
    protected $columnsNames;
    protected $isActive;

    protected $formName;

    public function __construct() {
        // add types in constructor
//       $this->addType('name', 'string');
//        $this->addType('defaultpath', 'string');
    }
}