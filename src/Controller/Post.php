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

    public function add($uri, $format = 'html') {
        echo "Adding...";
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

        $this->view->addToBlock("main", 'import://posts/inbox');
        $this->view->setLayout('posts/dashboard');

    }
}