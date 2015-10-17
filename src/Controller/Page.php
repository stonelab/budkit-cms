<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;
use Budkit\Cms\Helper\ErrorNotFoundException;
use Budkit\Cms\Model\Media\Content;
use Budkit\Cms\Model\User;
use Budkit\Datastore\Model\Entity;
use Budkit\Event\Event;
use Budkit\Helper\Time;
use Parsedown;

class Page extends Controller
{


    public function index($format = 'html', $uri = "")
    {
        return $this->read($uri, $format);
    }


    public function read($uri, $format = 'html')
    {
        if (empty($uri)){
            //Checks for a homepage in the settings
            $uri = $this->application->config->get("setup.site.homepage");
            //sets a default template, can be overwritten if a page is loaded;
            $template = "homepage";
        }

        //NO need to check read permission again
        //$this->checkPermission("execute");
        if (!empty($uri)):

            //2. load the page;
            $page = $this->application->createInstance(Content::class);
            $page = $page->defineValueGroup("page");
            $page = $page->getMedia("page", $uri);
            //echo "Browsing in {$format} format";

            //$parser  = new \Parsedown::instance();
            //$editing = $page->getPropertyData();
            //$editing["media_content"] =  html_entity_decode($editing["media_content"]);

            //throw a not found exception if the page id does not exists
            if (!isset($page["items"]) || count($page["items"]) < 1) {
                throw new ErrorNotFoundException("The requested page does not exist");
                return false;
            }

            $read = reset($page["items"]);

            //lets fix the content;
            $read["media_content"] = Parsedown::instance()
                // ->setBreaksEnabled(true) # enables automatic line breaks
                ->text($read["media_content"]);

            // 1. load the page;
            $template = !empty($read["media_template"]) ? $read["media_template"] : null; //determine page template from

            //show a page or load custom page template
            $this->view->setData("title", (!empty($read["media_title"]) ? $read["media_title"] : "Page"));
            $this->view->setData("reading", $read);


        endif;

        if (!empty($template)) {

            //if uri is null
            // 2. load a bunch of templates;
            //Trigger Layout.onLoad.page.template.definitions
            $templateDefinitions = [];
            $loadPageTemplates = new Event('Layout.onLoad.page.template.definitions', $this);
            $loadPageTemplates->setResult($templateDefinitions);

            $this->observer->trigger($loadPageTemplates);

            $definedTemplates = $loadPageTemplates->getResult();

            foreach ($definedTemplates as $_ => $def) {
                //add the look up paths to the layout so the view can be found
                if(!isset($def["name"]) || !isset($def["source"])) continue;
                if($def["name"] === $template) $this->view->appendLayoutSearchPath($def["source"]);
            }
        }

        $layout = "pages/page";
        $layout .= empty($template) ? "-default" : "-" . $template;


        $this->view->setLayout($layout);

    }

    public function edit($uri, $format = 'html')
    {

        //page-templates

        //1. check this uer has permission to execute /page/create

        $this->checkPermission("execute");

        //2. load the page;
        $page = $this->application->createInstance(Content::class);
        $page = $page->defineValueGroup("page");
        $page = $page->loadObjectByURI($uri);

        //print_R($page->getPropertyData());
        //$parser  = new \Parsedown::instance();
        $editing = $page->getPropertyData();

        if(isset($editing["media_content"])):
            $editing["media_content"] = html_entity_decode($editing["media_content"]);
        endif;

        //Editing Anouncement
        $onEdit = new  Event('Layout.onEdit', $this, ["editing" => $editing]);
        $onEdit->setResult($editing); //set initial result
        $this->observer->trigger($onEdit); //Parse the Node;
        $editing = $onEdit->getResult();

        //Load page templates
        $templateDefinitions = [["name"=>"homepage","source"=>null]];
        $loadPageTemplates = new Event('Layout.onLoad.page.template.definitions', $this);
        $loadPageTemplates->setResult($templateDefinitions);
        $this->observer->trigger($loadPageTemplates);
        $templateDefinitions = $loadPageTemplates->getResult();

        foreach($templateDefinitions as $tid=>$template){

            if(!isset($template["name"])){
                unset($templateDefinitions[$tid]);
                continue;
            }
            $template["current"] =
                ($template["name"] === $editing["media_template"]) ? "yes" : "no";

            $templateDefinitions[$tid] = $template;
        }

        $this->view->setData("templates", $templateDefinitions);
        $this->view->setData("editor", "page");
        $this->view->setData("title", "Create New Page");
        $this->view->setData("editing", $editing);
        $this->view->setData("object_uri", $uri);
        $this->view->setData("csrftoken", $this->application->session->getCSRFToken());

        $this->view->setLayout("editor");

    }

    public function add()
    {
        echo "Adding...";
    }

    public function delete()
    {
        echo "Delete...";
    }

    public function create()
    {

        //1. check this uer has permission to execute /page/create
        $this->checkPermission("execute");

        //2. create a blank page and redirect to edit;
        $page = $this->application->createInstance(Content::class);
        $page = $page->defineValueGroup("page");

        if (!$page->store()) {
            // throw new Exception("could not create the page");

            echo "page not created";
            die;

        }

        return $this->application->dispatcher->redirect("/page/{$page->getLastSavedObjectURI()}/edit");
    }

    public function update($uri, $format = 'html')
    {

        //1. check this uer has permission to execute /page/create
        $this->checkPermission("execute");

        //2. are we patching or updating an existing?
        $input = $this->application->input;
        $user = new User($this->application, $this->application->database, $this->application->session);

        if ($input->methodIs("PATCH")) { //because we are updating;


            //3. load the page;
            $page = $this->application->createInstance(Content::class);
            $page = $page->defineValueGroup("page");
            $page = $page->loadObjectByURI($uri);


            //4. Is this a valid page?
            if ($page->getObjectId()) { //if we have a page;

                //Checks if the current user is the owner of this page or has special permissions to edit pages
                if ($page->getPropertyValue("media_owner") == $user->getCurrentUser()->getPropertyValue("user_name_id") || $this->permission("/page/edit", null, "special")) {

                    //we will save the content as HTML
                    $page = $this->bindData($page); //binds input data;
                    $page->setPropertyValue("media_published", Time::stamp());
                    $page->defineValueGroup("page");
                    if ($page->saveObject($page->getObjectURI(), $page->getObjectType())) {

                        $this->response->addAlert(t("Your page content has been updated successfully"), "success");

                        //Redirect to dashboard or to last url?
                        return $this->application->dispatcher->redirect("/page/{$page->getObjectURI()}/edit", HTTP_FOUND, null, $this->response->getAlerts());

                    }
                }
            }
        }

        $this->response->addAlert(t("The page content was not updated"), "warning");

        //Redirect to dashboard or to last url?
        return $this->application->dispatcher->redirect("/page/{$page->getObjectURI()}/edit", HTTP_FOUND, null, $this->response->getAlerts());
    }

    public function replace()
    {
        echo "Replacing...";
    }

    public function options()
    {
        echo "Options...";
    }


    private function bindData(Entity $page)
    {

        $inputModel = $page->getPropertyModel();

        // print_R($_POST);
        foreach ($inputModel as $property => $definition):
            $value = $this->application->input->getString($property, "", "post");
            if (!empty($value)):
                $page->setPropertyValue($property, $value);
            endif;
        endforeach;


        //Allow some HTML in media content;
        $mediaContent = $this->application->input->getFormattedString("media_content", "", "post", true);

        $page->setPropertyValue("media_content", $mediaContent);

        return $page;

    }
}
