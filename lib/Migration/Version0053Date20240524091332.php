<?php

declare(strict_types=1);

namespace OCA\ZendExtract\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

/**
 * Auto-generated migration step: Please modify to your needs!
 */
class Version0053Date20240524091332 extends SimpleMigrationStep {

        /**
         * @param IOutput $output
         * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
         * @param array $options
         */
        public function preSchemaChange(IOutput $output, Closure $schemaClosure, array $options): void {
        }

        /**
         * @param IOutput $output
         * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
         * @param array $options
         * @return null|ISchemaWrapper
         */
        public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
                /** @var ISchemaWrapper $schema */
                $schema = $schemaClosure();

                if (!$schema->hasTable('zendextract_extractions')) {
                        $table = $schema->createTable('zendextract_extractions');
                        $table->addColumn('id', 'integer', [
                                'autoincrement' => true,
                                'notnull' => true,
                                'length' => 4,
                        ]);
                        $table->addColumn('name', 'string', [
                                'notnull' => true,
                                'length' => 100,
                        ]);
                        $table->addColumn('default_path', 'string', [
                                'notnull' => true,
                                'length' => 1000,
                                'default' => 'test',
                        ]);
                        $table->addColumn('brand_id', 'bigint', [
                                'notnull' => false,
                                'length' => 1000,
                                'default' => 0,
                        ]);
                        $table->addColumn('group_id', 'string', [
                                'notnull' => true,
                        ]);
                        $table->setPrimaryKey(['id']);
                        $table->addUniqueIndex(['name'], 'name_index');
                        $table->addIndex(['brand_id'], 'brand_index');
                }

                if (!$schema->hasTable('zendextract_forms')) {
                        $table = $schema->createTable('zendextract_forms');
                        $table->addColumn('id', 'integer', [
                                'autoincrement' => true,
                                'notnull' => true,
                                'length' => 4,
                        ]);
                        $table->addColumn('form_id', 'string', [
                                'notnull' => true,
                        ]);
                        $table->addColumn('extraction_id', 'integer', [
                                'notnull' => true,
                                'length' => 4,
                        ]);
                        $table->addColumn('name', 'string', [
                                'notnull' => true,
                                'length' => 100,
                        ]);
                        $table->addColumn('display_name', 'string', [
                                'notnull' => true,
                                'length' => 300,
                        ]);
                        $table->setPrimaryKey(['id']);
                        $table->addIndex(['extraction_id'], 'extractionid_index');
                }

                if (!$schema->hasTable('zendextract_fields')) {
                        $table = $schema->createTable('zendextract_fields');
                        $table->addColumn('id', 'integer', [
                                'autoincrement' => true,
                                'notnull' => true,
                                'length' => 4,
                        ]);
                        $table->addColumn('field_id', 'string', [
                                'notnull' => true,
                                'length' => 50,
                        ]);
                        $table->addColumn('form_id', 'string', [
                                'notnull' => true,
                        ]);
                        $table->addColumn('extraction_id', 'integer', [
                                'notnull' => true,
                                'length' => 4,
                        ]);
                        $table->addColumn('order_index', 'integer', [
                                'notnull' => true,
                                'length' => 4,
                                'default' => 0,
                        ]);
                        $table->addColumn('title', 'string', [
                                'notnull' => true,
                                'length' => 1000,
                        ]);
                        $table->addColumn('type', 'string', [
                                'notnull' => true,
                                'length' => 100,
                        ]);
                        $table->addColumn('column_name', 'string', [
                                'notnull' => false,
                                'length' => 1000,
                        ]);
                        $table->addColumn('custom_field_type', 'integer', [
                                'notnull' => true,
                                'length' => 4,
                                'default' => 0,
                        ]);
                        $table->addColumn('date_format', 'string', [
                                'notnull' => false,
                                'length' => 100,
                        ]);
                        $table->addColumn('custom_text', 'string', [
                                'notnull' => false,
                                'length' => 200,
                        ]);
                        $table->addColumn('nb_columns', 'integer', [
                                'notnull' => false,
                                'length' => 4,
                        ]);
                        $table->addColumn('columns_names', 'string', [
                                'notnull' => false,
                                'length' => 100,
                        ]);
                        $table->addColumn('is_active', 'boolean', [
                                'notnull' => false,
                        ]);
                        $table->addColumn('is_merged', 'boolean', [
                                'notnull' => false,
                        ]);
                        $table->addColumn('merge_name', 'string', [
                                'notnull' => false,
                                'length' => 100,
                        ]);
                        $table->setPrimaryKey(['id']);
                        $table->addIndex(['form_id'], 'formid_index');
                }

                if (!$schema->hasTable('zendextract_brands')) {
                        $table = $schema->createTable('zendextract_brands');
                        $table->addColumn('id', 'integer', [
                                'autoincrement' => true,
                                'notnull' => true,
                                'length' => 4,
                        ]);
                        $table->addColumn('name', 'string', [
                                'notnull' => true,
                                'length' => 100,
                        ]);
                        $table->setPrimaryKey(['id']);
                        $table->addUniqueIndex(['name'], 'name_index');
                }
                return $schema;
        }

        /**
         * @param IOutput $output
         * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
         * @param array $options
         */
        public function postSchemaChange(IOutput $output, Closure $schemaClosure, array $options): void {
        }
}