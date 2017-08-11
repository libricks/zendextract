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
use OCA\ZendExtract\Db\Field;

class FieldMapper extends Mapper
{
    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'zendextract_fields');
    }

    /**
     * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
     */
    public function find($id)
    {
        $sql = 'SELECT * FROM `*PREFIX*zendextract_fields` ' .
            'WHERE `id` = ?';
        return $this->findEntity($sql, [$id]);
    }


    public function findAllByFormId($formId, $limit, $offset)
    {
        $sql = 'SELECT * FROM `*PREFIX*zendextract_fields WHERE form_id = ? ORDER BY order_index`';
        return $this->findEntities($sql, [$formId], $limit, $offset);
    }

    public function disactiveAllFieldsByExtraction($extractionId)
    {
        $sql = 'UPDATE  `*PREFIX*zendextract_fields` as fields 
                INNER JOIN `*PREFIX*zendextract_forms` as forms ON forms.id = fields.form_id
                INNER JOIN `*PREFIX*zendextract_extractions` as extractions ON forms.extraction_id = extractions.id
                SET is_active = false
                WHERE extractions.id = ?';


        $this->execute($sql, [$extractionId]);

        $sql = 'UPDATE  `*PREFIX*zendextract_fields` as fields 
                INNER JOIN `*PREFIX*zendextract_extractions` as extractions ON fields.extraction_id = extractions.id
                SET is_active = false
                WHERE extractions.id = ?';


        $this->execute($sql, [$extractionId]);
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
if($selected){
    $sql = "
           
                    SELECT fields.*, forms.name as formname
                    FROM `*PREFIX*zendextract_fields` as fields 
                    INNER JOIN `*PREFIX*zendextract_extractions` as extractions ON extractions.id = fields.extraction_id
                    LEFT JOIN `*PREFIX*zendextract_forms` as forms ON forms.id = fields.form_id
                    WHERE fields.extraction_id = ? AND fields.is_active = 1
                   ORDER BY fields.order_index
                   
         ";
}else{
    $sql = "
           
                    SELECT fields.*, forms.name as formname
                    FROM `*PREFIX*zendextract_fields` as fields 
                    INNER JOIN `*PREFIX*zendextract_extractions` as extractions ON extractions.id = fields.extraction_id
                    LEFT JOIN `*PREFIX*zendextract_forms` as forms ON forms.id = fields.form_id
                    WHERE fields.extraction_id = ?
                   ORDER BY fields.order_index
                   
         ";

}

        $stmt = $this->execute($sql, [$extractionId ]);



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
            $f->setFormName($row["formname"]);
            $fields[] = $f;

        }

        $stmt->closeCursor();

        return $fields;
    }

    public function findByExtractionAndFieldId($extractionId, $fieldId)
    {
        $sql = 'SELECT * FROM `*PREFIX*zendextract_fields` ' .
            'WHERE `extraction_id` = ? AND field_id = ?';

        $stmt = $this->execute($sql, [$extractionId, $fieldId]);

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
        $f->setFormName($row["formname"]);
        $stmt->closeCursor();
        return $f;
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
