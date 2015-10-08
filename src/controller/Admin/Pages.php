<?php

namespace Budkit\Cms\Controller\Admin;

use Budkit\Cms\Provider;
use Budkit\Cms\Controller\Admin;
use Budkit\Application\Platform as Application;

class Pages extends Admin {


    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";

        $this->view->addData("action", ["title"=>"Add Page","link"=>"/page/create", "class"=>"btn-primary"]);

       // echo "Pages admin";
        $this->view->setData("title", t("Pages"));

        //$this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/dashboard");

    }
}