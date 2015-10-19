<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;

class Message extends Controller {

    public function index($format = 'html') {
        //echo "Browsing in {$format} format";
        //echo "Browsing in {$format} format";

        //echo "Searching... directory";
        //print_r( $this->application->config );
        $this->view->setData("title", "Messages");
        $this->view->setData("page", ["body"=>["class"=>"container-block"]]);


        //$this->view->addToBlock("navbar-button", 'import://messages/navbar-button');

        //Tell the view where to find additional layouts
        $this->view->addToBlock("main", 'import://messages/inbox');
        $this->view->setLayout('messages/dashboard');

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
        echo "Creating...";
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