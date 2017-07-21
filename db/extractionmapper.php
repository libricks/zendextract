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

use OCP\IDBConnection;
use OCP\AppFramework\Db\Mapper;
class ExtractionMapper extends Mapper
{
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'zendextract_extractions');
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
        $sql = 'SELECT * FROM `*PREFIX*zendextract_extractions` ';
        return $this->findEntities($sql);
    }
}