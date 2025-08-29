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

use OCP\IDBConnection;
use OCP\AppFramework\Db\Mapper;
class ExtractionMapper extends Mapper
{
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'ze_extractions');
    }

    /**
     * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
     */
    public function find($id) {
        $sql = 'SELECT * FROM `*PREFIX*ze_extractions` ' .
            'WHERE `id` = ?';
        return $this->findEntity($sql, [$id]);
    }


    public function findAll() {
        $sql = 'SELECT * FROM `*PREFIX*ze_extractions` ORDER BY CONVERT(`name`USING UTF8) ';
        return $this->findEntities($sql);
    }
    public function findbyGroupId($id){
        $sql = 'SELECT * FROM `*PREFIX*ze_extractions` ' .
            'WHERE `group_id` = ?';
        return $this->findEntities($sql, [$id]);
    }
}
