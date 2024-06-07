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

class BrandMapper extends QBMapper
{
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'ze_brands');
    }

    /**
     * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
     */
    public function find($id) {
        // $sql = 'SELECT * FROM `*PREFIX*zendextract_brands` ' .
        //     'WHERE `id` = ?';
        // return $this->findEntity($sql, [$id]);

        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from('ze_brands', 'brands')
            ->where($qb->expr()->eq('brands.id', $qb->createNamedParameter($id)));
        return $this->findEntities($qb);
    }


    public function findAll() {
        // $sql = 'SELECT * FROM `*PREFIX*zendextract_brands` ';
        // return $this->findEntities($sql);

        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from('ze_brands', 'brands');
        return $this->findEntities($qb);
    }
}
