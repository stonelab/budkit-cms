<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;


class Admin extends Controller {


    public function index($format="html") {
        //echo "Browsing in {$format} format";



        //print_R($this->application->database);

        $this->view->setData("title", "Console");
        $this->view->setLayout("member/dashboard");

    }

}