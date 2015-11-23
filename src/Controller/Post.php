<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;

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
        $this->view->addToBlock("main", 'import://posts/single');
        $this->view->setLayout('posts/dashboard');

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


    private function timeline(){

        $this->view->addToBlock("main", 'import://posts/inbox');
        $this->view->setLayout('posts/dashboard');

    }
}