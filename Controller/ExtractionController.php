<?php
/**
 * nextCloud - Zendesk Xtractor
 *
 * @author Marc-Henri Pamiseux <mhp@libricks.org>
 * @copyright Marc-Henri Pamiseux 2017
 * @license AGPL
 * @license https://opensource.org/licenses/AGPL-3.0
 */

namespace OCA\ZendExtract\Controller;

require_once __DIR__ . '/../vendor/autoload.php';
//use Zendesk\API\HttpClient as ZendeskAPI;

use Couchbase\Exception;
use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

use OCA\ZendExtract\Db\ExtractionMapper;
use OCA\ZendExtract\Db\FormMapper;
use OCA\ZendExtract\Db\Extraction;
use OCA\ZendExtract\Db\Field;
use OCA\ZendExtract\Db\FieldMapper;
use OCA\ZendExtract\Db\Form;

use OCP\IURLGenerator;
use OCA\ZendExtract\Service\ZendDeskAPI;
use OCP\AppFramework\Http\RedirectResponse;
use OCA\ZendExtract\Service\ExtractionStorage;
use OCP\Files\IRootFolder;
use OCP\ILogger;
use \DateTime;

class ExtractionController extends Controller
{
    private $userId;
    private $extractionMapper;
    private $formMapper;
    private $fieldMapper;
    private $urlGenerator;
    private $root;
    private $zendDeskAPI;
    private $logger;
    private $webRoot;

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     */
    public function __construct(
        $AppName,
        IRequest $request,
        $UserId,
        ExtractionMapper $extractionMapper,
        FormMapper $formMapper,
        FieldMapper $fieldMapper,
        IURLGenerator $urlGenerator,
        IRootFolder $rootfolder,
        ILogger $logger,
        $webRoot,
        ZendDeskAPI $zendDeskAPI)
    {

        parent::__construct($AppName, $request);

        $this->userId = $UserId;
        $this->extractionMapper = $extractionMapper;
        $this->formMapper = $formMapper;
        $this->fieldMapper = $fieldMapper;
        $this->urlGenerator = $urlGenerator;
        $this->root = $rootfolder;
        $this->zendDeskAPI = $zendDeskAPI;
        $this->logger = $logger;
        $this->webRoot = $webRoot;
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     */
    public function index()
    {
        $extractions = $this->extractionMapper->findAll();

        return new TemplateResponse('zendextract', 'index', array(
            'webRoot' => $this->webRoot,
            'view' => "index",
            'extractions' => $extractions));  // templates/index.php
    }


    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function step($step, $id)
    {
        $extraction = $this->extractionMapper->find($id);
        if ($step == 1) {

            $all_forms = $this->zendDeskAPI->get("/api/v2/ticket_forms.json");
            $selected_forms_ids = $this->formMapper->findByExtractionId($id);

            return new TemplateResponse('zendextract', 'index', array(
                    'webRoot' => $this->webRoot,
                    'view' => "step1",
                    'step' => 1,
                    'forms' => $all_forms,
                    'selected_forms_ids' => $selected_forms_ids,
                    'extraction' => $extraction)
            );  // templates/index.php

        } else if ($step == 2) {
            $fields = $this->fieldMapper->findAllByExtractionId($id);

            return new TemplateResponse('zendextract', 'index',
                array(
                    'webRoot' => $this->webRoot,
                    'view' => "step2",
                    'step' => $step,
                    'extraction' => $extraction,
                    'fields' => $fields)
            );  // templates/index.php
        } else if ($step == 3) {
            $fields = $this->fieldMapper->findAllByExtractionId($id, true);
            return new TemplateResponse('zendextract', 'index',
                array(
                    'webRoot' => $this->webRoot,
                    'view' => "step3",
                    'extraction' => $extraction,
                    'fields' => $fields)
            );
        }
    }


    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function create()
    {


        $all_forms = $this->zendDeskAPI->get("/api/v2/ticket_forms.json");


        return new TemplateResponse('zendextract', 'index', array(
                'webRoot' => $this->webRoot,
                'view' => "step1",
                'forms' => $all_forms,
                'selected_forms_ids' => array(),
                'extraction' => new Extraction())
        );  // templates/index.php
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function delete($id)
    {

        $extraction = $this->extractionMapper->find($id);

        return new TemplateResponse('zendextract', 'index', array(
                'webRoot' => $this->webRoot,
                'view' => "deleteconfirm",
                'extraction' =>$extraction)
        );  // templates/index.php
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function deleteConfirm($id)
    {

        $extraction = $this->extractionMapper->find($id);

        $this->formMapper->deleteByExtractionId($id);

        $this->extractionMapper->delete($extraction);

        return new RedirectResponse($this->webRoot.'/index.php/apps/zendextract/');
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     */
    public function step1POST($name, $forms, $defaultpath = "", $id = null)
    {


        if ($id === nulll) {
            $extraction = $this->extractionMapper->find($id);
        } else {
            try {
                $e = new Extraction();
                $e->setName($name);
                $e->setDefaultPath($defaultpath);
                $extraction = $this->extractionMapper->insert($e);
            } catch (\Exception $e) {
                $this->logger->error("Extraction déjà créée " . $e->getMessage(), array('app' => $this->appName));
                return new RedirectResponse($this->webRoot.'/index.php/apps/zendextract/');
            }
        }


        foreach ($forms as $form) {


            $result = $this->zendDeskAPI->get("/api/v2/ticket_forms/$form.json");


            $form = new Form();
            $form->setName($result->ticket_form->name);
            $form->setDisplayName($result->ticket_form->display_name);
            $form->setExtractionId($extraction->getId());
            $form->setFormId($result->ticket_form->id);
            $this->formMapper->insert($form);


            if ($id == null) {

                $base_fields = array("id", "channel", "created_at", "modified_at", "type", "subject", "description", "status", "recipient");

                foreach ($base_fields as $title) {
                    $f = new Field();
                    $f->setFormId($form->getId());
                    $f->setTitle($title);
                    $f->setColumnName($title);
                    $f->setType("base");
                    $f->setIsActive(false);
                    $this->fieldMapper->insert($f);
                }

            }

            $fields = $result->ticket_form->ticket_field_ids;

            foreach ($fields as $field) {
                $field = $this->zendDeskAPI->get("/api/v2/ticket_fields/$field.json");

                $database_field = $this->fieldMapper->findByFormAndFieldId($field->ticket_field->id, $f->id);
                if ($database_field == null) {
                    $f = new Field();

                    $f->setFormId($form->getId());
                    $f->setFieldId($field->ticket_field->id);
                    $f->setTitle($field->ticket_field->title);
                    $f->setColumnName($field->ticket_field->title);
                    $f->setType($field->ticket_field->type);
                    $f->setIsActive(false);
                    $this->fieldMapper->insert($f);
                } else {
                    $database_field->setFiedId($field->ticket_field->id);
                    $database_field->setTitle($field->ticket_field->title);
                    $database_field->setType($field->ticket_field->type);
                    $this->fieldMapper->update($database_field);
                }

            }

        }
        return new RedirectResponse($this->webRoot."/index.php/apps/zendextract/extraction/step/2/" . $extraction->id);
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     */
    public function step2POST($id, $selected_fields)
    {

        $field = $this->fieldMapper->disactiveAllFieldsByExtraction($id);

        foreach ($selected_fields as $field_id) {

            $field = $this->fieldMapper->find($field_id);

            $field->setIsActive(true);
            $this->fieldMapper->update($field);

        }

        return new RedirectResponse($this->webRoot."/index.php/apps/zendextract/extraction/step/3/" . $id);
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     */
    public function step3POST($id, $fields)
    {

        $i = 0;
        foreach ($fields as $field) {

            $f = $this->fieldMapper->find($field["id"]);
            $f->setColumnName($field["column_name"]);
            $f->setCustomFieldType($field["custom_field_type"]);

            $f->setDateFormat($field["date_format"]);
            $f->setNbColumns($field["nb_columns"]);
            $f->setColumnsNames($field["columns_names"]);
            $f->setOrderIndex($i);

            $this->fieldMapper->update($f);

            $i++;
        }


        return new RedirectResponse($this->webRoot."/index.php/apps/zendextract/extraction/step/3/" . $id);
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     */
    public function export($id=null)
    {

        $extractions = $this->extractionMapper->findAll();
        return new TemplateResponse('zendextract', 'index', array(
            'webRoot' => $this->webRoot,
            'view' => "export",
            'extractions' => $extractions,
            'extractionId' => $id));  // templates/index.php
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     */
    public function generate($extractionId, $from, $to, $type)
    {
        $extraction = $this->extractionMapper->find($extractionId);
        try {
            $forms = $this->formMapper->findByExtractionId($extractionId);

            $tickets = array();
            //   $query = "type:ticket created>2017-07-17 ";
            $query = "type:ticket";
            foreach ($forms as $form) {
                $query = $query . " ticket_form_id:" . $form;

            }

            $start = DateTime::createFromFormat('d/m/Y', $from);


            $end = DateTime::createFromFormat('d/m/Y', $to);


            while ($start <= $end) {
                $query = $query . " custom_field_23982008:" . $start->format("Y-m-d ");
                $start->modify("+1 day");
            }

            if ($type == "1") {
                $query = $query . " custom_field_23964913:f_information";
            } else if ($type == "2") {
                $query = $query . " custom_field_23964913:f_réclamation";
            }


            $result = $this->zendDeskAPI->get("/api/v2/search.json?query=" . urlencode($query));

            $tickets = $result->results;

            while ($result->next_page != null) {
                $result = $this->zendDeskAPI->getAbsolute($result->next_page);
                $tickets = array_merge($tickets, $result->results);
            }


        } catch (Httpful\Exception $e) {
            $this->logger->error("Problème lors de la récupération des tickets " . $e->getMessage(), array('app' => $this->appName));
        }
        $fields = $this->fieldMapper->findAllByExtractionId($extractionId, true);

        $arrayCSV = array();
        $row = array();
        foreach ($fields as $field) {
            if ($field->getCustomFieldType() == 2) {
                $columnsNames = $field->getColumnsNames();
                $explode = explode(",", $columnsNames);
                for ($i = 0; $i < $field->getNbColumns(); $i++) {
                    $row[] = trim($explode[$i]);
                }
            } else {
                $row[] = $field->getColumnName();
            }


        }
        $arrayCSV[] = $row;
        $customFieldsOptions = array();
        foreach ($tickets as $ticket) {

            $row = array();
            $value = "";
            foreach ($fields as $field) {
                if ($field->getType() == "base") {
                    switch ($field->getTitle()) {
                        case "id":
                            $value = $ticket->id;
                            break;
                        case "channel":
                            $value = $ticket->via->channel;
                            break;
                        case "created_at":
                            $value = $ticket->created_at;
                            break;
                        case "modified_at":
                            $value = $ticket->modified_at;
                            break;
                        case "type":
                            $value = $ticket->type;
                            break;
                        case "subject":
                            $value = $ticket->subject;
                            break;
                        case "description":
                            $value = $ticket->description;
                            break;
                        case "status":
                            $value = $ticket->status;
                            break;
                        case "recipient":
                            $value = $ticket->recipient;
                            break;
                    }

                } else {
                    $value = $this->customfieldsSearch($ticket, $field->getFieldId());
                }

                if ($field->getCustomFieldType() == 1) {
                    setlocale(LC_TIME, "fr_FR");
                    $value = strftime($field->getDateFormat(), (new DateTime($value))->getTimestamp());
                    $row[] = $value;
                } else if ($field->getCustomFieldType() == 2) {

                    $options = array();
                    if (array_key_exists($field->getFieldId(), $customFieldsOptions)) {
                        $options = $customFieldsOptions[$field->getFieldId()];
                    } else {
                        $result = $this->zendDeskAPI->get("/api/v2/ticket_fields/" . $field->getFieldId() . ".json");
                        $customFieldsOptions[$field->getFieldId()] = $result->ticket_field->custom_field_options;
                        $options = $customFieldsOptions[$field->getFieldId()];
                    }
                    $found = 0;
                    $i = 0;

                    while (!$found && $i < count($options)) {
                        if ($options[$i]->value == $value) {
                            $value = $options[$i]->name;
                            $found = 1;
                        }
                        $i++;
                    }

                    $explode = explode("::", $value);
                    $explode = array_reverse($explode);
                    $explode = array_slice($explode, 0, $field->getNbColumns());
                    $array_result = array();
                    for ($i = 0; $i < $field->getNbColumns(); $i++) {
                        $array_result[] = $explode[$i];
                    }
                    $array_result = array_reverse($array_result);
                    for ($i = 0; $i < count($array_result); $i++) {
                        $row[] = $array_result[$i];
                    }
                } else {
                    $row[] = $value;
                }


            }
            $arrayCSV[] = $row;
        }

        try {
            \OC::$server->getRootFolder()->get($this->userId . "/files/Extractions/");
        } catch (\OCP\Files\NotFoundException $ex) {
            \OC::$server->getRootFolder()->get($this->userId . "/files/")->newFolder("Extractions");
        }

        try {
            $folder = \OC::$server->getRootFolder()->get($this->userId . "/files/Extractions/" . $extraction->getName());
        } catch (\OCP\Files\NotFoundException $ex) {
            $folder = \OC::$server->getRootFolder()->get($this->userId . "/files/Extractions/")->newFolder($extraction->getName());
        }

        $dt = new DateTime();
        $filename = $dt->format('Y-m-d H:i:s');


        $file = $folder->newFile($filename . ".csv");

        $fileResource = $file->fopen('w');
        foreach ($arrayCSV as $row) {

            fputcsv($fileResource, $row, ";");
        }

        fclose($file);
        return new TemplateResponse('zendextract', 'index', array(
            'webRoot' => $this->webRoot,
            'view' => "generate",
            'tickets' => $tickets,
            'folder' => $folder,
            'file' => $file));  // templates/index.php
    }


    private function customfieldsSearch($ticket, $customFieldId)
    {
        $orderedCustomFields = $ticket->custom_fields;

        usort($orderedCustomFields, array($this, 'customFieldSort'));


        $min = 0;
        $max = count($orderedCustomFields) + 1;
        $find = 0;

        while (!$find && (($max - $min) > 1)) {

            $index = (int)(($min + $max) / 2);


            if ($orderedCustomFields[$index]->id > $customFieldId) {
                $max = $index;
            } else if ($orderedCustomFields[$index]->id < $customFieldId) {

                $min = $index;

            } else {

                $find = 1;
            }


        }

        if ($find) {
            return $orderedCustomFields[$index]->value;
        }

        return "";

        //23653097
    }

    private static function customFieldSort($a, $b)
    {

        if ($a->id == $b->id) return 0;
        return ($a->id < $b->id) ? -1 : 1;
    }
}
