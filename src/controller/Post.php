<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;

class Post extends Controller {

    public function index($format = 'html') {
        //echo "Browsing in {$format} format";

        $this->view->setData("name", "Livingstone");
        $this->view->setLayout("inbox/index");
    }


    public function read($id, $format = 'html') {
        echo "Reading {$id} in {$format} format";

        $this->view->setData("name", "Livingstone");
        $this->view->setLayout("inbox/index");
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