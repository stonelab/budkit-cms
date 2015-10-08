<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;
use Budkit\Cms\Model\Media\Content;
use Whoops\Example\Exception;

class Page extends Controller {


    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";

        $this->checkPermission("execute");

        //die;

        $this->view->setData("name", "Livingstone");
        $this->view->setLayout("index");

        //@TODO redirect to the homepage

    }


    public function read($uri, $format = 'html') {
        echo "Reading {$uri} in {$format} format";
    }

    public function edit($uri, $format = 'html') {

        //1. check this uer has permission to execute /page/create
        $this->checkPermission("execute");

        //2. load the page;
        $page = $this->application->createInstance( Content::class );
        $page = $page->defineValueGroup("page");
        $page = $page->loadObjectByURI($uri);

        //print_R($page->getPropertyData());

        $this->view->setData("editor", "page");
        $this->view->setData("title", "Create New Page");
        $this->view->setData("editing", $page->getPropertyData());
        $this->view->setData("object_uri", $uri);
        $this->view->setData("csrftoken", $this->application->session->getCSRFToken());

        $this->view->setLayout("editor");

    }

    public function add() {
        echo "Adding...";
    }

    public function delete() {
        echo "Delete...";
    }

    public function create() {

        //1. check this uer has permission to execute /page/create
        $this->checkPermission("execute");

        //2. create a blank page and redirect to edit;
        $page = $this->application->createInstance( Content::class );
        $page = $page->defineValueGroup("page");

        if(!$page->store()){
           // throw new Exception("could not create the page");

            echo "page not created";
            die;

        }

        $this->application->dispatcher->redirect("/page/{$page->getLastSavedObjectURI()}/edit");
    }

    public function update($uri, $format = 'html') {

        //1. check this uer has permission to execute /page/create
        $this->checkPermission("execute");

        //2. are we patching or updating an existing?
        $input = $this->application->input;

        if ($input->methodIs("PATCH")) { //beceause we are updating;
            //$this->setPropertyValue("media_published", Time::stamp());


            //Allow some HTML in media content;
            $mediaContent = $input->getFormattedString("media_content", "", "post", true);


            print_R($mediaContent);

            //$this->setPropertyValue("media_published", Time::stamp());


            die;

        }


    }

    public function replace() {
        echo "Replacing...";
    }

    public function options() {
        echo "Options...";
    }
}