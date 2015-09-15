<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Provider;
use Budkit\Dependency\Container as Application;

class Message extends Controller {

    public function __construct(Application $application) {
        parent::__construct($application);

        $this->view->appendLayoutSearchPath( Provider::getPackageDir()."layouts/");
    }

    public function index($format = 'html') {
        //echo "Browsing in {$format} format";

        $this->display(Budkit\Cms\View\Index::class);
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