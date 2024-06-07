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

use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;

class ExtractionMapper extends QBMapper
{
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'ze_extractions');
    }

    /**
     * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
     */
    public function find($id) {
        $sql = 'SELECT * FROM `*PREFIX*zendextract_extractions` ' .
            'WHERE `id` = ?';
        return $this->findEntity($sql, [$id]);
    }


    public function findAll() {
        // $sql = 'SELECT * FROM `*PREFIX*zendextract_extractions` ORDER BY CONVERT(`name`USING UTF8) ';
        // return $this->findEntities($sql);
        $queryBuilder = $this->db->getQueryBuilder();

        $queryBuilder->select('*')
            ->from('ze_extractions')
            ->orderBy('name');

        return $this->findEntities($queryBuilder);
    }
    public function findbyGroupId($id){
        $sql = 'SELECT * FROM `*PREFIX*zendextract_extractions` ' .
            'WHERE `group_id` = ?';
        return $this->findEntities($sql, [$id]);
    }
}
