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
use OCA\ZendExtract\Db\Field;

class FieldMapper extends QBMapper
{
    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'ze_fields');
    }

    /**
     * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
     */
    public function find($id)
    {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('ze_fields')
            ->where($query->expr()->eq('id', $query->createNamedParameter($id)));

        return $this->findEntity($query);
        // $sql = 'SELECT * FROM `*PREFIX*zendextract_fields` ' .
        //     'WHERE `id` = ?';
        // return $this->findEntity($sql, [$id]);
    }


    public function findAllByFormId($formId, $limit, $offset)
    {
        // $sql = 'SELECT * FROM `*PREFIX*zendextract_fields WHERE form_id = ? ORDER BY order_index`';
        // return $this->findEntities($sql, [$formId], $limit, $offset);
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('ze_fields')
            ->where($query->expr()->eq('form_id', $query->createNamedParameter($formId)))
            ->orderBy('order_index')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $this->findEntities($query);
    }

    public function disactiveAllFieldsByExtraction($extractionId)
    {
        $query = $this->db->getQueryBuilder();
        $query->update('ze_fields', 'fields')
            ->innerJoin('fields', 'ze_forms', 'forms', 'forms.id = fields.form_id')
            ->innerJoin('forms', 'ze_extractions', 'extractions', 'forms.extraction_id = extractions.id')
            ->set('is_active', $query->expr()->literal(false))
            ->where($query->expr()->eq('extractions.id', $query->createNamedParameter($extractionId)));

        $this->execute($query);

        $query = $this->db->getQueryBuilder();
        $query->update('ze_fields', 'fields')
            ->innerJoin('fields', 'ze_extractions', 'extractions', 'fields.extraction_id = extractions.id')
            ->set('is_active', $query->expr()->literal(false))
            ->where($query->expr()->eq('extractions.id', $query->createNamedParameter($extractionId)));

        $this->execute($query);
        // $sql = 'UPDATE  `*PREFIX*zendextract_fields` as fields 
        //         INNER JOIN `*PREFIX*zendextract_forms` as forms ON forms.id = fields.form_id
        //         INNER JOIN `*PREFIX*zendextract_extractions` as extractions ON forms.extraction_id = extractions.id
        //         SET is_active = false
        //         WHERE extractions.id = ?';


        // $this->execute($sql, [$extractionId]);

        // $sql = 'UPDATE  `*PREFIX*zendextract_fields` as fields 
        //         INNER JOIN `*PREFIX*zendextract_extractions` as extractions ON fields.extraction_id = extractions.id
        //         SET is_active = false
        //         WHERE extractions.id = ?';


        // $this->execute($sql, [$extractionId]);
    }

    /**
     *
     * function findAllByExtractionId is not documented
     *
     * @access  public
     * @param   integer $extractionId
     * @param   boolean $selected , active fields or all fields
     * @return  Array, tableau des fields
     */
    public function findAllByExtractionId($extractionId, $selected = false)
    {
        $query = $this->db->getQueryBuilder();
        $query->select('fields.*, forms.name as formname')
            ->from('ze_fields', 'fields')
            ->innerJoin('fields', 'ze_extractions', 'extractions', $query->expr()->eq('extractions.id', $query->createNamedParameter($extractionId)))
            ->leftJoin('fields', 'ze_forms', 'forms', $query->expr()->eq('forms.id', 'fields.form_id'))
            ->where($query->expr()->eq('fields.extraction_id', $query->createNamedParameter($extractionId)));

        if ($selected) {
            $query->andWhere($query->expr()->eq('fields.is_active', $query->expr()->literal(true)));
        }

        $query->orderBy('fields.order_index');

        $stmt = $this->execute($query);
        $fields = array();
        while ($row = $stmt->fetch()) {
            $f = new Field();
            $f->setFieldId($row["field_id"]);
            $f->id = $row["id"];
            $f->setFormId($row["form_id"]);
            $f->setExtractionId($row["extraction_id"]);
            $f->setFieldId($row["field_id"]);
            $f->setOrderIndex($row["order_index"]);
            $f->setTitle($row["title"]);
            $f->setType($row["type"]);
            $f->setColumnName($row["column_name"]);
            $f->setCustomFieldType($row["custom_field_type"]);
            $f->setDateFormat($row["date_format"]);
            $f->setNbColumns($row["nb_columns"]);
            $f->setColumnsNames($row["columns_names"]);
            $f->setCustomText($row["custom_text"]);
            $f->setIsActive($row["is_active"]);
            $f->setIsMerged($row["is_merged"]);
            $f->setMergeName($row["merge_name"]);
            $f->setFormName($row["formname"]);
            $fields[] = $f;
        }

        $stmt->closeCursor();
        return $fields;

// if($selected){
//     $sql = "
           
//                     SELECT fields.*, forms.name as formname
//                     FROM `*PREFIX*zendextract_fields` as fields 
//                     INNER JOIN `*PREFIX*zendextract_extractions` as extractions ON extractions.id = fields.extraction_id
//                     LEFT JOIN `*PREFIX*zendextract_forms` as forms ON forms.id = fields.form_id
//                     WHERE fields.extraction_id = ? AND fields.is_active = 1
//                    ORDER BY fields.order_index
                   
//          ";
// }else{
//     $sql = "
           
//                     SELECT fields.*, forms.name as formname
//                     FROM `*PREFIX*zendextract_fields` as fields 
//                     INNER JOIN `*PREFIX*zendextract_extractions` as extractions ON extractions.id = fields.extraction_id
//                     LEFT JOIN `*PREFIX*zendextract_forms` as forms ON forms.id = fields.form_id
//                     WHERE fields.extraction_id = ?
//                    ORDER BY fields.order_index
                   
//          ";

// }

//         $stmt = $this->execute($sql, [$extractionId ]);



//         $fields = array();
//         while ($row = $stmt->fetch()) {

//             $f = new Field();

//             $f->setFieldId($row["field_id"]);
//             $f->id = $row["id"];
//             $f->setFormId($row["form_id"]);
//             $f->setExtractionId($row["extraction_id"]);
//             $f->setFieldId($row["field_id"]);
//             $f->setOrderIndex($row["order_index"]);
//             $f->setTitle($row["title"]);
//             $f->setType($row["type"]);
//             $f->setColumnName($row["column_name"]);
//             $f->setCustomFieldType($row["custom_field_type"]);
//             $f->setDateFormat($row["date_format"]);
//             $f->setNbColumns($row["nb_columns"]);
//             $f->setColumnsNames($row["columns_names"]);
//             $f->setCustomText($row["custom_text"]);
//             $f->setIsActive($row["is_active"]);
//             $f->setIsMerged($row["is_merged"]);
//             $f->setMergeName($row["merge_name"]);
//             $f->setFormName($row["formname"]);
//             $fields[] = $f;

//         }

//         $stmt->closeCursor();

//         return $fields;
    }

    public function findByExtractionAndFieldId($extractionId, $fieldId)
    {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('ze_fields')
            ->where($query->expr()->eq('extraction_id', $query->createNamedParameter($extractionId)))
            ->andWhere($query->expr()->eq('field_id', $query->createNamedParameter($fieldId)));

        $stmt = $this->execute($query);
        $row = $stmt->fetch();
        if (!$row) {
            $stmt->closeCursor();
            return null;
        }
        $f = new Field();
        $f->setFieldId($row["field_id"]);
        $f->id = $row["id"];
        $f->setFormId($row["form_id"]);
        $f->setExtractionId($row["extraction_id"]);
        $f->setFieldId($row["field_id"]);
        $f->setOrderIndex($row["order_index"]);
        $f->setTitle($row["title"]);
        $f->setType($row["type"]);
        $f->setColumnName($row["column_name"]);
        $f->setCustomFieldType($row["custom_field_type"]);
        $f->setDateFormat($row["date_format"]);
        $f->setNbColumns($row["nb_columns"]);
        $f->setColumnsNames($row["columns_names"]);
        $f->setCustomText($row["custom_text"]);
        $f->setIsActive($row["is_active"]);
        $f->setIsMerged($row["is_merged"]);
        $f->setMergeName($row["merge_name"]);
        $f->setFormName($row["formname"]);
        $stmt->closeCursor();
        return $f;
        // $sql = 'SELECT * FROM `*PREFIX*zendextract_fields` ' .
        //     'WHERE `extraction_id` = ? AND field_id = ?';

        // $stmt = $this->execute($sql, [$extractionId, $fieldId]);

        // $row = $stmt->fetch();


        // if (!$row) {
        //     $stmt->closeCursor();
        //     return null;
        // }

        // $f = new Field();

        // $f->setFieldId($row["field_id"]);
        // $f->id = $row["id"];
        // $f->setFormId($row["form_id"]);
        // $f->setExtractionId($row["extraction_id"]);
        // $f->setFieldId($row["field_id"]);
        // $f->setOrderIndex($row["order_index"]);
        // $f->setTitle($row["title"]);
        // $f->setType($row["type"]);
        // $f->setColumnName($row["column_name"]);
        // $f->setCustomFieldType($row["custom_field_type"]);
        // $f->setDateFormat($row["date_format"]);
        // $f->setNbColumns($row["nb_columns"]);
        // $f->setColumnsNames($row["columns_names"]);
        // $f->setCustomText($row["custom_text"]);
        // $f->setIsActive($row["is_active"]);
        // $f->setIsMerged($row["is_merged"]);
        // $f->setMergeName($row["merge_name"]);
        // $f->setFormName($row["formname"]);
        // $stmt->closeCursor();
        // return $f;
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
