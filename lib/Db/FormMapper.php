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

class FormMapper extends QBMapper
{
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'ze_forms');
    }

    /**
     * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
     */
    public function find($id) {
        $sql = 'SELECT * FROM `*PREFIX*zendextract_forms` ' .
            'WHERE `id` = ?';
        return $this->findEntity($sql, [$id]);
    }

	public function findNameByExtractionId($extractionId)
	{

		$sql = 'SELECT `name` FROM `*PREFIX*zendextract_forms` ' .
		       'WHERE `extraction_id` = ?';


		$stmt = $this->execute($sql, [$extractionId]);
		$ids = array();
		while($row = $stmt->fetch()){
			$names[] = $row["name"];
		}




		$stmt->closeCursor();

		return $names;
	}


    public function findByExtractionId($extractionId)
    {

        $sql = 'SELECT form_id FROM `*PREFIX*zendextract_forms` ' .
            'WHERE `extraction_id` = ?';


        $stmt = $this->execute($sql, [$extractionId]);
        $ids = array();
        while($row = $stmt->fetch()){
            $ids[] = $row["form_id"];
        }




        $stmt->closeCursor();

        return $ids;
    }

    public function deleteByExtractionId($extractionId)
    {

        $sql = 'DELETE fields FROM `*PREFIX*zendextract_fields` as fields
                INNER JOIN `*PREFIX*zendextract_forms` as forms ON fields.form_id = forms.id
                WHERE forms.extraction_id = ?';
        $stmt = $this->execute($sql, [$extractionId]);

        $sql = 'DELETE FROM `*PREFIX*zendextract_forms` WHERE extraction_id = ?';
        $stmt = $this->execute($sql, [$extractionId]);

        $stmt->closeCursor();

    }
//
//
//    public function findAll($limit=null, $offset=null) {
//        $sql = 'SELECT * FROM `*PREFIX*myapp_authors`';
//        return $this->findEntities($sql, $limit, $offset);
//    }
//
//
//    public function authorNameCount($name) {
//        $sql = 'SELECT COUNT(*) AS `count` FROM `*PREFIX*myapp_authors` ' .
//            'WHERE `name` = ?';
//        $stmt = $this->execute($sql, [$name]);
//
//        $row = $stmt->fetch();
//        $stmt->closeCursor();
//        return $row['count'];
//    }
}
