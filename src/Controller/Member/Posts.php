<?php

namespace Budkit\Cms\Controller\Member;

use Budkit\Cms\Controller\Member;

class Posts extends Member {


    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";


        $this->view->addData("action", ["title"=>"Add Post","link"=>"/post/create", "class"=>"btn-primary"]);

        $this->view->setData("title", t("Posts"));

        //$this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/dashboard");

    }
}