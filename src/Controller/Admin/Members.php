<?php

namespace Budkit\Cms\Controller\Admin;

use Budkit\Cms\Model\Authority;
use Budkit\Cms\Controller\Admin;

class Members extends Admin {


    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";


        $this->view->addData("action", ["title"=>"Add Member","link"=>"/member/create", "class"=>"btn-primary"]);

       // echo "Pages admin";
        $this->view->setData("title", t("Members"));

        $this->view->addToBlock("main", "import://member/member-list");
        $this->view->setLayout("member/dashboard");
    }


}