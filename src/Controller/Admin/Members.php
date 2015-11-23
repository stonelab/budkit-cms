<?php

namespace Budkit\Cms\Controller\Admin;

use Budkit\Cms\Model\Authority;
use Budkit\Cms\Controller\Admin;

class Members extends Admin {


    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";


       // echo "Pages admin";
        $this->view->setData("title", t("Members"));

        //$this->view->addToBlock("main", "import://admin/settings/configuration");
        //$this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/dashboard");
    }


}