<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;

class Post extends Controller {

    public function index($format = 'html') {
        //echo "Browsing in {$format} format";
        //echo "Browsing in {$format} format";

        //echo "Searching... directory";
        //print_r( $this->application->config );
        $this->view->setData("title", "Inbox");
       // $this->view->setData("page", ["body"=>["class"=>"container-block"]]);


        $this->view->addData("action", ["title"=>"New Post","link"=>"/post/create", "class"=>"btn-primary"]);
        //$this->view->addData("action", ["title"=>"Compose","link"=>"/message/create", "class"=>"btn-primary"]);


        //$this->view->addToBlock("navbar-button", 'import://messages/navbar-button');

        //Tell the view where to find additional layouts
        $this->view->addToBlock("main", 'import://posts/inbox');
        $this->view->setLayout('posts/dashboard');

    }


    public function read($id, $format = 'html') {
        echo "Reading {$id} in {$format} format";


    }

    public function edit($id = 'new', $format = 'html') {
        echo "Editing {$id} in {$format} format";
    }

    public function add() {
        echo "Adding...";
    }

    public function delete() {
        echo "Delete...";
    }

    public function create() {

        $this->view->setData("editor", "post");
        $this->view->setData("title", "Create New Post");

        $this->view->setLayout("editor");
    }

    public function update() {
        echo "Updating...";
    }

    public function replace() {
        echo "Replacing...";
    }

    public function options() {
        echo "Options...";
    }
}