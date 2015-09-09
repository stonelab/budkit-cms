<?php

namespace App\Application\Controller;

use Budkit\Routing\Controller;
use Budkit\Dependency\Container as Application;

class Inbox extends Controller {

    public function __construct(Application $application) {
        parent::__construct($application);

        $this->view->appendLayoutSearchPath( Provider::getPackageDir()."layouts/");
    }


    public function index() {

        //var_dump($this->view);
        //$this->response->setContentType("pdf");

        //throw new Exception("Something broke!");
        //var_dump($this->response);
        //echo "what about this";

        $this->view->setData("name", "Livingstone");
        $this->view->setLayout("inbox/index");
    }


}