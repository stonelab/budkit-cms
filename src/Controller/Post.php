<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;
use Budkit\Cms\Model\Media\Content;

class Post extends Controller {


    public function index($format = 'html') {

        $this->view->setData("title", "Timeline");
        //$this->view->addToBlock("stream", '');

        $this->timeline();

    }


    public function read($id, $format = 'html') {

        //We are going to add a single Item;
        //$this->index();

        //Change the title
        $title = "Reading {$id} in {$format} format";

        $this->view->setData("title", $title );

        //add the single stream

        //$this->view->setData("object_uri", $uri);
        $this->view->setData("csrftoken", $this->application->session->getCSRFToken());
        $this->view->addToBlock("main", 'import://posts/post-single');
        $this->view->setLayout('posts/post-dashboard');

    }

    public function edit($id = 'new', $format = 'html') {
        echo "Editing {$id} in {$format} format";
    }

    public function put($format = 'html') {

        //1. check this uer has permission to execute /page/create
        $this->checkPermission("execute");

        //2. are we patching or updating an existing?
        $input = $this->application->input;

        if ($input->methodIs("post")) { //because we are updating;


            //3. load the page;
            $content = $this->application->createInstance(Content::class);
            // $page = $page->defineValueGroup("page");
            //$page = $page->loadObjectByURI($uri);

            print_r($input->data("post"));

        }

        //Is the user authenticated?
        // $this->requireAuthentication();
        //Is the input method submitted via POST;


    }

    public function delete($uri, $format = 'html') {
        echo "Delete...";
    }

    public function create($uri, $format = 'html') {

        $this->view->setData("editor", "post");
        $this->view->setData("title", "Create New Post");

        $this->view->setLayout("editor");
    }

    public function update($uri, $format = 'html') {
        echo "Updating...";
    }

    public function replace($uri, $format = 'html') {
        echo "Replacing...";
    }

    public function options($uri, $format = 'html') {
        echo "Options...";
    }


    private function timeline(){


        //$this->view->addData("action", ["title"=>"Map","link"=>"/member/timeline/map", "class"=>"btn-primary"]);

        //$this->view->setData("object_uri", $uri);
        $this->view->setData("csrftoken", $this->application->session->getCSRFToken());
        $this->view->addToBlock("main", 'import://posts/post-inbox');
        $this->view->setLayout('posts/post-dashboard');

    }
}