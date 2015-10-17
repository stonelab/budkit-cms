<?php

namespace Budkit\Cms\Controller\Member;

use Budkit\Cms\Controller\Member;

class Settings extends Member {


    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";

       // echo "Pages admin";
        $this->view->setData("title", t("Settings"));

        //$this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/dashboard");

    }

}