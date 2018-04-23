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
class groupMapper extends Mapper
{
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'zendextract_extractions');
    }

    /**
     * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
     */
    public function find($id) {
        $sql = 'SELECT oc_groups.gid FROM `*PREFIX*group_user` INNER JOIN `*PREFIX*groups` ON `*PREFIX*groups`.gid=`*PREFIX*group_user`.gid ' .
            'WHERE `*PREFIX*groups`.gid = ?';
        return $this->findEntities($sql, [$id]);
    }


    public function findAll() {
        $sql = 'SELECT * FROM `*PREFIX*groups` ORDER BY CONVERT(`gid`USING UTF8) ';
        return $this->findEntities($sql);
    }
    public function findByUserId($user){
        $sql = $sql = 'SELECT oc_groups.gid FROM `*PREFIX*group_user` INNER JOIN `*PREFIX*groups` ON `*PREFIX*groups`.gid=`*PREFIX*group_user`.gid ' .
            'WHERE `*PREFIX*group_user`.uid= ? ';
        return $this->findEntities($sql,[$user]);
    }
}
