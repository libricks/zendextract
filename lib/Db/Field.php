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

class Field extends Entity
{

    protected $formId;
    protected $extractionId;
    protected $fieldId;
    protected $orderIndex;
    protected $title;
    protected $type;

    protected $columnName;
    protected $customFieldType;
    protected $dateFormat;
    protected $nbColumns;
    protected $columnsNames;
    protected $customText;
    protected $isActive;
    protected $isMerged;
    protected $mergeName;
    protected $formName;

    public function __construct() {
        // add types in constructor
        $this->addType('formId', 'integer');
        $this->addType('extractionId', 'integer');
        $this->addType('fieldId', 'integer');
        $this->addType('orderIndex', 'integer');
        $this->addType('title', 'string');
        $this->addType('type', 'string');
        $this->addType('columnName', 'string');
        $this->addType('customFieldType', 'string');
        $this->addType('dateFormat', 'string');
        $this->addType('nbColumns', 'integer');
        $this->addType('columnsNames', 'string');
        $this->addType('customText', 'string');
        $this->addType('isActive', 'boolean');
        $this->addType('isMerged', 'boolean');
        $this->addType('mergeName', 'string');
        $this->addType('formName', 'string');






    }

}
