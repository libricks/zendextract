<?php

/**
 * nextCloud - Zendesk Xtractor
 *
 * This file is licensed under the GNU Affero General Public License version 3
 * or later. See the COPYING file.
 *
 * @category    Controller
 * @package     OCA\ZendExtract\Controller
 * @author      Tawfiq Cadi Tazi <tawfiq@caditazi.fr>
 * @copyright   Copyright (C) 2017 SARL LIBRICKS
 * @license     AGPL
 * @license     https://opensource.org/licenses/AGPL-3.0
 * @version     0.1 [<description>]
 * @see         https://docs.nextcloud.com/server/12/developer_manual/app/controllers.html NextCloud Documentation
 */

namespace OCA\ZendExtract\Controller;
error_reporting(E_ALL);
ini_set("display_errors", 1);
/**
 *
 */
require_once __DIR__ . '/../Vendor/autoload.php';

//use Zendesk\API\HttpClient as ZendeskAPI;

use Couchbase\Exception;
use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

use OCA\ZendExtract\Db\BrandMapper;
use OCA\ZendExtract\Db\Brand;
use OCA\ZendExtract\Db\ExtractionMapper;
use OCA\ZendExtract\Db\FormMapper;
use OCA\ZendExtract\Db\Extraction;
use OCA\ZendExtract\Db\Field;
use OCA\ZendExtract\Db\FieldMapper;
use OCA\ZendExtract\Db\Group;
use OCA\ZendExtract\Db\GroupMapper;
use OCA\ZendExtract\Db\Form;

use OCP\IURLGenerator;
use OCA\ZendExtract\Service\ZendDeskAPI;
use OCP\AppFramework\Http\RedirectResponse;
use OCA\ZendExtract\Service\ExtractionStorage;
use OCP\Files\IRootFolder;
use OCP\ILogger;
use \DateTime;

/**
 *
 *
 */

class ExtractionController extends Controller
{
    // {{{ properties

    /**
     * @var     integer $userId Potential values are ...
     * @access  private
     */
    private $userId;

    /**
     * @var     integer $extractionMapper Potential values are ...
     * @access  private
     */
    private $extractionMapper;

    /**
     * @var     integer $brandMapper Potential values are ...
     * @access  private
     */
    private $brandMapper;

    /**
     * @var     integer $formMapper Potential values are ...
     * @access  private
     */
    private $formMapper;

    /**
     * @var     integer $fieldMapper Potential values are ...
     * @access  private
     */
    private $fieldMapper;

    /**
     * @var     integer $groupMapper Potential values are ...
     * @access  private
     */
    private $groupMapper;

    /**
     * @var     integer $urlGenerator Potential values are ...
     * @access  private
     */
    private $urlGenerator;

    /**
     * @var     integer $root Potential values are ...
     * @access  private
     */
    private $root;

    /**
     * @var     integer $zendDeskAPI Potential values are ...
     * @access  private
     */
    private $zendDeskAPI;

    /**
     * @var     integer $logger Potential values are ...
     * @access  private
     */
    private $logger;

    /**
     * @var     integer $webRoot Potential values are ...
     * @access  private
     */
    private $webRoot;



    // }}}

    // {{{ __construct()

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * constructor of the controller
     *
     * @param string $appName
     * @param IRequest $request
     * @param integer $UserId
     * @param ExtractionMapper $extractionMapper
     * @param FormMapper $formMapper
     * @param FieldMapper $fieldMapper
     * @param IURLGenerator $urlGenerator
     * @param IRootFolder $rootfolder
     * @param ILogger $logger
     * @param string $webRoot
     * @param ZendDeskAPI $zendDeskAPI
     */
    public function __construct($appName,
                                IRequest $request,
                                $UserId,
                                BrandMapper $brandMapper,
                                ExtractionMapper $extractionMapper,
                                FormMapper $formMapper,
                                FieldMapper $fieldMapper,
                                GroupMapper $groupMapper,
                                IURLGenerator $urlGenerator,
                                IRootFolder $rootfolder,
                                ILogger $logger,
                                $webRoot,
                                ZendDeskAPI $zendDeskAPI)
    {

        parent::__construct($appName, $request);

        $this->userId = $UserId;
        $this->brandMapper = $brandMapper;
        $this->extractionMapper = $extractionMapper;
        $this->formMapper = $formMapper;
        $this->fieldMapper = $fieldMapper;
        $this->groupMapper = $groupMapper;
        $this->urlGenerator = $urlGenerator;
        $this->root = $rootfolder;
        $this->zendDeskAPI = $zendDeskAPI;
        $this->logger = $logger;
        $this->webRoot = $webRoot;
    }
    // }}}

    // {{{ index()
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * function index is not documented
     *
     * @access  public
     * @return  TemplateResponse
     */
    public function index()
    {
        $extractions= array();
        $groups = $this->groupMapper->findByUserId($this->userId);
        foreach ($groups as $group){
            if($group->getGid()=='admin'){
                $extractions = $this->extractionMapper->findAll();
                $groups = $this->groupMapper->findAll();
                break;
            }else{
                $temp= $this->extractionMapper->findbyGroupId($group->getGid());
                $extractions = array_merge($extractions, $temp);
            }
         }
        //création d'une variable tableau extractions contenant toutes les extractions

        //vérifier si un des groupes est "admin"
        //si oui donner toutes les exttraction
        //si non
            //pour chaque groupe de l'utilisateur
                //récuperer dans $les_extractions_du_groupe_actuel les extractions du groupe
                //ajouter les extractions du groupe à $extractinos



        return new TemplateResponse('zendextract', 'index', array(
            'webRoot' => $this->webRoot,
            'view' => "index",
            'group' => $groups,
            'extractions' => $extractions));
        // templates/index.php
    }
    // }}}

    // {{{ step()
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * function step is not documented
     *
     * @access  public
     * @param   string $step
     * @param   integer $id
     * @return  TemplateResponse
     */
    public function step($step, $id)
    {
        $extraction = $this->extractionMapper->find($id);
        if ($step == 1) {

            $brands = $this->brandMapper->findAll();

            return new TemplateResponse('zendextract', 'index', array(
                    'brands' => $brands,
                    'webRoot' => $this->webRoot,
                    'view' => "step1",
                    'step' => 1,
                    'extraction' => $extraction)
            );

        } else if ($step == 2) {
            $fields = $this->fieldMapper->findAllByExtractionId($id);

            return new TemplateResponse('zendextract', 'index',
                array(
                    'mode' => 'edit',
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
    // }}}


    // {{{ create()
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @todo    function create is not documented
     * @access  public
     * @return  TemplateResponse
     */
    public function create()
    {

        $all_forms = $this->zendDeskAPI->get("  ticket_forms.json");
        $brands = $this->brandMapper->findAll();
        $groups = $this->groupMapper->findByUserId($this->userId);
        return new TemplateResponse('zendextract', 'index', array(
                'brands' => $brands,
                'mode' => 'create',
                'webRoot' => $this->webRoot,
                'view' => "step1",
                'forms' => $all_forms,
                'groups' => $groups,
                'extraction' => new Extraction())
        );  // templates/index.php
    }
    // }}}

    // {{{ delete()
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @todo    function delete is not documented
     * @access  public
     * @param   integer $id
     * @return  TemplateResponse
     */
    public function delete($id)
    {

        $extraction = $this->extractionMapper->find($id);

        return new TemplateResponse('zendextract', 'index', array(
                'webRoot' => $this->webRoot,
                'view' => "deleteconfirm",
                'extraction' => $extraction)
        );  // templates/index.php
    }
    // }}}

    // {{{ deleteConfirm()
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @todo    function deleteConfirm is not documented
     * @access  public
     * @param   integer $id
     * @return  RedirectResponse
     */
    public function deleteConfirm($id)
    {
        $extraction = $this->extractionMapper->find($id);
        $this->formMapper->deleteByExtractionId($id);
        $this->extractionMapper->delete($extraction);

        return new RedirectResponse($this->webRoot . '/index.php/apps/zendextract/');
    }
    // }}}

    // {{{ step1POST()
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @todo    function step1POST is not documented
     * @access  public
     * @param   string $name
     * @param   collection $forms
     * @param   string $defaultpath |empty
     * @param   integer $id |null
     * @return  RedirectResponse
     */
    public function step1POST($name, $forms, $defaultpath = "", $mode, $brand_id, $newbrand, $id = null,$group)
    {

        //Création de l'extraction

        if ($mode == "create") {
            //Création des champs de base
            try {
                $e = new Extraction();
                $e->setName($name);
                $e->setDefaultPath($defaultpath);
                $e->setGroupId($group);
                $extraction = $this->extractionMapper->insert($e);
            } catch (\Exception $e) {
                //Si l'extraction est déjà créée
                $this->logger->error("Extraction déjà créée " . $e->getMessage(), array('app' => $this->appName));
                return new RedirectResponse($this->webRoot . '/index.php/apps/zendextract/');
            }
            $order_index = 1000;

            if ($id == null) {

                $base_fields = array("id", "form_name", "channel", "created_at", "modified_at", "type", "subject", "description", "status", "recipient");

                foreach ($base_fields as $title) {
                    $f = new Field();
                    $f->setExtractionId($extraction->getId());
                    $f->setTitle($title);
                    $f->setColumnName($title);
                    $f->setType("base");
                    $f->setIsActive(false);
                    $f->setOrderIndex($order_index++);
                    $this->fieldMapper->insert($f);
                }

                $conversation_fields = array("conversation niveau 2", "conversation niveau 3");

                foreach ($conversation_fields as $title) {
                    $f = new Field();
                    $f->setExtractionId($extraction->getId());
                    $f->setTitle($title);
                    $f->setColumnName($title);
                    $f->setType("conversation");
                    $f->setIsActive(false);
                    $f->setOrderIndex($order_index++);
                    $this->fieldMapper->insert($f);
                }
            }


            //Création des champs pour chaque formulaire
            foreach ($forms as $form) {
                $result = $this->zendDeskAPI->get("/api/v2/ticket_forms/$form.json");

                $form = new Form();
                $f->setFormId($form->getId());
                $form->setName($result->ticket_form->name);
                $form->setDisplayName($result->ticket_form->display_name);
                $form->setExtractionId($extraction->getId());
                $form->setFormId($result->ticket_form->id);
                $this->formMapper->insert($form);

                $fields = $result->ticket_form->ticket_field_ids;

                foreach ($fields as $field) {
                    $field = $this->zendDeskAPI->get("/api/v2/ticket_fields/$field.json");
                    $database_field = $this->fieldMapper->findByExtractionAndFieldId($extraction->getId(), $field->ticket_field->id);

                    if ($database_field == null) {
                        $f = new Field();

                        $f->setFormId($form->getId());
                        $f->setFieldId($field->ticket_field->id);
                        $f->setTitle($field->ticket_field->title);
                        $f->setColumnName($field->ticket_field->title);
                        $f->setType($field->ticket_field->type);
                        $f->setIsActive(false);
                        $f->setOrderIndex($order_index++);
                        $f->setExtractionId($extraction->getId());
                        $this->fieldMapper->insert($f);
                    } else {
                        $database_field->setFieldId($field->ticket_field->id);
                        $database_field->setTitle($field->ticket_field->title);
                        $database_field->setType($field->ticket_field->type);
                        $database_field->setFormId(0);
                        $this->fieldMapper->update($database_field);
                    }
                }
            }
        } else {
            $extraction = $this->extractionMapper->find($id);
            $extraction->setName($name);
            $this->extractionMapper->update($extraction);
        }


        if (isset($newbrand) && trim($newbrand) != "") {

            $brand = new Brand();
            $brand->setName($newbrand);
            $brand = $this->brandMapper->insert($brand);
            $brand_id = $brand->getId();
        }


        $extraction->setBrandId($brand_id);
        $this->extractionMapper->update($extraction);


        return new RedirectResponse($this->webRoot . "/index.php/apps/zendextract/extraction/step/2/" . $extraction->id);
    }
    // }}}

    // {{{ step2POST()
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @todo    function step2POST is not documented
     * @access  public
     * @param   integer $id
     * @param   collection $selected_fields
     * @return  RedirectResponse
     */
    public function step2POST($id, $selected_fields)
    {

        $field = $this->fieldMapper->disactiveAllFieldsByExtraction($id);

        foreach ($selected_fields as $field_id) {

            $field = $this->fieldMapper->find($field_id);

            $field->setIsActive(true);
            $this->fieldMapper->update($field);

        }

        return new RedirectResponse($this->webRoot . "/index.php/apps/zendextract/extraction/step/3/" . $id);
    }
    // }}}


    // {{{ step2POST()
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @todo    function step2POST is not documented
     * @access  public
     * @param   integer $id
     * @param   collection $selected_fields
     * @return  RedirectResponse
     */
    public function step2UPDATE($id)
    {

        $forms = $this->formMapper->findByExtractionId($id);
        $order_index = 2000;
        //Création des champs pour chaque formulaire
        foreach ($forms as $form) {
            $result = $this->zendDeskAPI->get("/api/v2/ticket_forms/$form.json");
            $fields = $result->ticket_form->ticket_field_ids;

            foreach ($fields as $field) {

                $database_field = $this->fieldMapper->findByExtractionAndFieldId($id, $field);

                if ($database_field == null) {
                    $field = $this->zendDeskAPI->get("/api/v2/ticket_fields/$field.json");

                    $f = new Field();

                    $f->setFormId($form);
                    $f->setFieldId($field->ticket_field->id);
                    $f->setTitle($field->ticket_field->title);
                    $f->setColumnName($field->ticket_field->title);
                    $f->setType($field->ticket_field->type);
                    $f->setIsActive(false);
                    $f->setOrderIndex($order_index++);
                    $f->setExtractionId($id);
                    $this->fieldMapper->insert($f);
                }
            }
        }
        return new RedirectResponse($this->webRoot . "/index.php/apps/zendextract/extraction/step/2/" . $id);
    }
    // }}}

    // {{{ step3POST()
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @todo    function step3POST is not documented
     * @access  public
     * @param   integer $id
     * @param   collection $fields
     * @return  RedirectResponse
     */
    public function step3POST($id, $fields)
    {

        $i = 0;
        foreach ($fields as $field) {

            $f = $this->fieldMapper->find($field["id"]);
            $f->setColumnName($field["column_name"]);
            $f->setCustomFieldType($field["custom_field_type"]);
            $f->setCustomText($field["custom_text"]);
            $f->setDateFormat($field["date_format"]);
            $f->setNbColumns($field["nb_columns"]);
            $f->setColumnsNames($field["columns_names"]);
            $f->setIsMerged(($field["is_merged"] == "on"));
            $f->setMergeName($field["merge_name"]);
            $f->setOrderIndex($i);

            $this->fieldMapper->update($f);

            $i++;
        }

        return new RedirectResponse($this->webRoot . "/index.php/apps/zendextract/extraction/step/3/" . $id);
    }
    // }}}

    // {{{ export()
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @todo    function export is not documented
     * @access  public
     * @param   integer $id |null
     * @return  TemplateResponse
     */
    public function export($id = null)
    {
        $groups = $this->groupMapper->findByUserId($this->userId);
        $brands = $this->brandMapper->findAll();
        $extractions= array();
        foreach ($groups as $group){
            if($group->getGid()=='admin'){
                $extractions = $this->extractionMapper->findAll();
                $groups =$this->groupMapper->findAll();
                break;
            }else{
                $temp= $this->extractionMapper->findbyGroupId($group->getGid());
                $extractions = array_merge($extractions, $temp);
            }
        }
        return new TemplateResponse('zendextract', 'index', array(
            'brands' => $brands,
            'webRoot' => $this->webRoot,
            'view' => "export",
            'extractions' => $extractions,
            'groups' => $groups,
            'extractionId' => $id));  // templates/index.php
    }

    // }}}

    private $convesations = array();

    // {{{ generate()

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @todo    function generate() is not documented
     * @access  public
     * @param   integer $extractionId
     * @param   string $from
     * @param   string $to
     * @param   string $type
     * @return  TemplateResponse
     */
    public function generate($extractionId, $fromTreatment, $toTreatment, $fromContact, $toContact, $fromCreate, $toCreate, $charset)
    {

     //   echo memory_get_usage ()."\t Avant première page \t ".date("d/m/Y G:i:s")."\t<br/>";
        //Récupération de l'extraction en BDD
        $extraction = $this->extractionMapper->find($extractionId);
        try {
            //Récupération des formulaires de l'extraction
            $forms = $this->formMapper->findByExtractionId($extractionId);

            $tickets = array();
            //$query = "type:ticket created>2017-07-17 ";
            $query = "type:ticket";

            //Filtrer les tickets par formulaire
            foreach ($forms as $form) {
                $query = $query . " ticket_form_id:" . $form;
            }


            //Variables pour le nom du fichier CSV
            $week_from = 0;
            $week_to = 0;
            $year = 0;

            if ($toTreatment != "" && $fromTreatment != "") {
                //Filtrer par date de traitement
                $start = DateTime::createFromFormat('d/m/Y', $fromTreatment);
                $end = DateTime::createFromFormat('d/m/Y', $toTreatment);

                while ($start <= $end) {
                    $query = $query . " custom_field_23982008:" . $start->format("Y-m-d ");
                    $start->modify("+1 day");
                }

                $week_from = $start->format("W");
                $week_to = $end->format("W");
                $year = $start->format("Y");
            }

            if ($fromContact != "" || $toContact != "") {
                //Filtrer par date de contact
                $start = DateTime::createFromFormat('d/m/Y', $fromContact);
                $end = DateTime::createFromFormat('d/m/Y', $toContact);

                while ($start <= $end) {
                    $query = $query . " custom_field_22188285:" . $start->format("Y-m-d ");
                    $start->modify("+1 day");
                }

                $week_from = $start->format("W");
                $week_to = $end->format("W");
                $year = $start->format("Y");
            }

            if ($fromCreate != "" || $toCreate != "") {
                //Filtrer par date de création
                $start = DateTime::createFromFormat('d/m/Y', $fromCreate);
                $end = DateTime::createFromFormat('d/m/Y', $toCreate);

                $query = $query . " created>" . $start->format("Y-m-d ") . " created<" . $end->format("Y-m-d ");

                $week_from = $start->format("W");
                $week_to = $end->format("W");
                $year = $start->format("Y");
            }


            //Récupération de tous les tickets
            $result = $this->zendDeskAPI->get("/api/v2/search.json?query=" . urlencode($query));
            foreach ($result->results as $ticket){
                $ticket->fields = null;
            }
            $tickets = $result->results;



      //      echo memory_get_usage ()."\t page 1 \t ".date("d/m/Y G:i:s")."\t<br/>";

            //S'il y a une pagination des résulats récupération de tous les tickets paginés
            $i = 2;
            while ($result->next_page != null) {
                $result = $this->zendDeskAPI->getAbsolute($result->next_page);
                foreach ($result->results as $ticket){
                    $ticket->fields = null;
                }
                $tickets = array_merge($tickets, $result->results);
          //      echo memory_get_usage ()."\t page ".$i++."\t ".date("d/m/Y G:i:s")."\t<br/>";
            }

        } catch (Httpful\Exception $e) {

            $this->logger->error("Problème lors de la récupération des tickets " . $e->getMessage(), array('app' => $this->appName));
        }

        //Récupération des champs de l'extraction
        $fields = $this->fieldMapper->findAllByExtractionId($extractionId, true);

        $arrayCSV = array();
        $row = array();


        //Ecriture de la lgine des titres

        $mergeName = "";
        foreach ($fields as $field) {

            if (!$field->getIsMerged() || $mergeName != $field->getMergeName()) {
                if ($field->getCustomFieldType() == 2) {
                    $columnsNames = $field->getColumnsNames();
                    $explode = explode(",", $columnsNames);
                    for ($i = 0; $i < $field->getNbColumns(); $i++) {

                        if ($charset == "utf8") {
                            $row[] = trim($explode[$i]);
                        } else {
                            $row[] = iconv("UTF-8", "windows-1252", trim($explode[$i]));
                        }
                    }
                } else {
                    if ($charset == "utf8") {
                        $row[] = $field->getColumnName();
                    } else {
                        $row[] = iconv("UTF-8", "windows-1252", $field->getColumnName());
                    }
                }
            }
            $mergeName = $field->getMergeName();
        }

        $arrayCSV[] = $row;
        $customFieldsOptions = array();


        //Pour chaque ticket
        $mergeName = "";
        $i = 0;
        foreach ($tickets as $ticket) {
        //    echo memory_get_usage ()."\t ticket \t ".date("d/m/Y G:i:s")."\t<br/>";
            $row = array();
            $value = "";

            foreach ($fields as $field) {
                if ($field->getIsMerged()) {
                    $form = $this->formMapper->find($field->getFormId());
                }

                if (!$field->getIsMerged() || $form->getFormId() == $ticket->ticket_form_id) {
                    if ($field->getType() == "base") {
                        switch ($field->getTitle()) {
                            case "id":
                                $value = $ticket->id;
                                break;
                            case "form_name":
                                $form = $this->zendDeskAPI->get("/api/v2/ticket_forms/" . $ticket->ticket_form_id . ".json");
                                $value = $form->ticket_form->name;
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

                    } else if ($field->getType() == "conversation") {
                        if (array_key_exists($ticket->id, $this->convesations)) {
                            $conversation = $this->convesations[$ticket->id];
                        } else {
                            $conversation = $this->zendDeskAPI->get("/api/v2/tickets/" . $ticket->id . "/comments.json");
                            $this->convesations[$ticket->id] = $conversation;
                        }
                        if ($field->getTitle() == "conversation niveau 2") {
                            if (count($conversation->comments) >= 2)
                                $value = $conversation->comments[1]->body;
                            else {
                                $value = "";
                            }
                        } else if ($field->getTitle() == "conversation niveau 3") {
                            if (count($conversation->comments) >= 3)
                                $value = $conversation->comments[2]->body;
                            else {
                                $value = "";
                            }
                        }
                 //       echo memory_get_usage ()."\t conversation \t ".date("d/m/Y G:i:s")."\t<br/>";

                    } else if ($field->getType() == "multiselect") {
                        $value = "";
                        $v = "";
                        $ArrayValue = $this->customfieldsSearch($ticket, $field->getFieldId(), $extractionId);
                        if($ArrayValue != null){
                            foreach ($ArrayValue as $v){
                                $v = trim($v, "_");
                                $value.="$v, ";
                            }
                            $value = substr($value, 0, strlen(($value)) -2);
                           // var_dump($value);
                        }



                    }  else {
                        $value = $this->customfieldsSearch($ticket, $field->getFieldId(), $extractionId);
                    }
                    $value = trim(preg_replace('/\s+/', ' ', $value));
                    if ($value == "31/12/1969") {
                        $value = "";
                    }

                    if ($field->getType() == "tagger" && $value == "1") {
                        $value = "Oui";
                    }

                    //Transformation de date


                    if ($field->getCustomFieldType() == 1) {
                        setlocale(LC_TIME, "fr_FR");
                        $value = strftime($field->getDateFormat(), (new DateTime($value))->getTimestamp());
                        $row[] = $value;
                        //Transformation liste ::
                    } else if ($field->getCustomFieldType() == 2) {

                        $options = array();
                        if (array_key_exists($field->getFieldId(), $customFieldsOptions)) {
                            $options = $customFieldsOptions[$field->getFieldId()];
                        } else {
                            $result = $this->zendDeskAPI->get("/api/v2/ticket_fields/" . $field->getFieldId() . ".json");
                            $customFieldsOptions[$field->getFieldId()] = $result->ticket_field->custom_field_options;
                            $options = $customFieldsOptions[$field->getFieldId()];
                        }
                        $found = false;
                        $i = 0;

                        while (!$found && $i < count($options)) {
                            if ($options[$i]->value == $value) {
                                $value = $options[$i]->name;
                                $found = true;
                            }
                            $i++;
                        }

                        $explode = explode("::", $value);
                        $explode = array_reverse($explode);
                        $explode = array_slice($explode, 0, $field->getNbColumns());
                        $explode = array_reverse($explode);
                        $array_result = array();
                        for ($i = 0; $i < $field->getNbColumns(); $i++) {
                            if (isset($explode[$i]))
                                $array_result[] = $explode[$i];
                            else
                                $array_result[] = "";
                        }
                        for ($i = 0; $i < count($array_result); $i++) {
                            if ($charset == "utf8") {
                                $row[] = $array_result[$i];
                            } else {
                                $row[] = iconv("UTF-8", "windows-1252", $array_result[$i]);
                            }
                        }

                        //Transformation valeur fixe
                    } else if ($field->getCustomFieldType() == 3) {

                        if ($charset == "utf8") {
                            $row[] = $field->getCustomText();
                        } else {
                            $row[] = iconv("UTF-8", "windows-1252", $field->getCustomText());
                        }

                        //Pas de transformation
                    } else {
                        if ($charset == "utf8") {
                            $row[] = $value;
                        } else {
                            $row[] = iconv("UTF-8", "windows-1252", $value);
                        }

                    }
                }
                $mergeName = $field->getMergeName();
            }
            $arrayCSV[] = $row;
            $tickets[$i] = null;
            $i++;
        }
       // echo memory_get_usage ()."\t début génération fichier \t ".date("d/m/Y G:i:s")."\t<br/>";

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

        $extraction = $this->extractionMapper->find($extractionId);


        $dt = new DateTime();
        //$filename = $dt->format('Y-m-d H:i:s');

        $extraction_name = str_replace("/", " ", $extraction->getName());

        if ($week_to == $week_from)
            $filename = $extraction_name . " -  semaine " . $week_from . " (" . $year . ")";
        else
            $filename = $extraction_name . " -  semaines " . $week_from . " à " . $week_to . " (" . $year . ")";
        $file = $folder->newFile($filename . ".csv");
        $fileResource = $file->fopen('w');

        foreach ($arrayCSV as $row) {
            fputcsv($fileResource, $row, ";");
        }



        fclose($file);

     //   echo memory_get_usage ()."\t fichier généré \t ".date("d/m/Y G:i:s")."\t<br/>";
        //die();
        return new TemplateResponse('zendextract', 'index', array(
            'webRoot' => $this->webRoot,
            'view' => "generate",
            'tickets' => $tickets,
            'folder' => $folder,
            'file' => $file));  // templates/index.php
    }

    // }}}


    private $custom_field_options = array();
    // {{{ customfieldsSearch()

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @todo    function customfieldsSearch() is not documented
     * @access  public
     * @param   integer $ticket
     * @param   integer $customFieldId
     * @return  string
     */
    private function customfieldsSearch($ticket, $customFieldId, $extractionId)
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

            $database_field = $this->fieldMapper->findByExtractionAndFieldId($extractionId, $orderedCustomFields[$index]->id);

            if ($database_field != null && $database_field->getType() == "tagger") {

                if (array_key_exists($orderedCustomFields[$index]->id, $this->custom_field_options)) {
                    $custom_field_option = $this->custom_field_options[$orderedCustomFields[$index]->id];
                } else {
                    $custom_field_option = $this->zendDeskAPI->get("/api/v2/ticket_fields/" . $orderedCustomFields[$index]->id . ".json");
                    $this->custom_field_options[$orderedCustomFields[$index]->id] = $custom_field_option;

                }
                foreach ($custom_field_option->ticket_field->custom_field_options as $option) {
                    if ($option->value == $orderedCustomFields[$index]->value) {
                        return $option->raw_name;
                    }
                }

            } else {
                return $orderedCustomFields[$index]->value;
            }
        }

        return "";

        //23653097
    }

// }}}

// {{{ customFieldSort()
    /**
     *
     * @todo    function customFieldSort() is not documented
     * @access  private static
     * @param   integer $a
     * @param   integer $b
     * @return  integer
     */
    private
    static function customFieldSort($a, $b)
    {
        if ($a->id == $b->id) return 0;
        return ($a->id < $b->id) ? -1 : 1;
    }
// }}}
}
