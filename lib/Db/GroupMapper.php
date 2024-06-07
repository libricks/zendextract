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

class GroupMapper extends QBMapper
{
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'ze_extractions');
    }

    /**
     * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
     */
    public function find($id) {
        // $sql = 'SELECT oc_groups.gid FROM `*PREFIX*group_user` INNER JOIN `*PREFIX*groups` ON `*PREFIX*groups`.gid=`*PREFIX*group_user`.gid ' .
        //     'WHERE `*PREFIX*groups`.gid = ?';
        // return $this->findEntities($sql, [$id]);

            $qb = $this->db->getQueryBuilder();
        $qb->select('groups.gid')
            ->from('groups', 'groups')
            ->innerJoin('groups', 'group_user', 'group_user', 'groups.gid=group_user.gid')
            ->where($qb->expr()->eq('groups.gid', $qb->createNamedParameter($id)));
        return $this->findEntities($qb);
    }


    public function findAll() {
        // $sql = 'SELECT * FROM `*PREFIX*groups` ORDER BY CONVERT(`gid`USING UTF8) ';
        // return $this->findEntities($sql);
        
        $qb = $this->db->getQueryBuilder();
        $qb->select('gid')
            ->from('groups', 'groups')
            ->orderBy('groups.gid');
        return $this->findEntities($qb);
    }
    public function findByUserId($user){
        // $sql = 'SELECT oc_groups.gid FROM `*PREFIX*group_user` INNER JOIN `*PREFIX*groups` ON `*PREFIX*groups`.gid=`*PREFIX*group_user`.gid ' .
        //     'WHERE `*PREFIX*group_user`.uid= ? ';
        // return $this->findEntities($sql,[$user]);

        $qb = $this->db->getQueryBuilder();
        $qb->select('groups.gid')
            ->from('groups', 'groups')
            ->innerJoin('groups', 'group_user', 'group_user', 'groups.gid=group_user.gid')
            ->where($qb->expr()->eq('group_user.uid', $qb->createNamedParameter($user)));
        return $this->findEntities($qb);
    }

    public function isAdmin($userId){

        
        //transforme cett requête en query bulder
         $qb = $this->db->getQueryBuilder();
        
        $qb->selectAlias($qb->createFunction('COUNT(groups.gid)'), 'count')
            ->from('groups', 'groups')
            ->innerJoin('groups', 'group_user', 'group_user', 'groups.gid=group_user.gid')
            ->where($qb->expr()->eq('group_user.uid', $qb->createNamedParameter($userId)))
            ->andWhere($qb->expr()->eq('groups.gid', $qb->createNamedParameter('admin')));
        
    
        $result = $qb->executeQuery();
        $row = $result->fetch();
 
        $result->closeCursor();
        return $row["count"] > 0;
    }
}
