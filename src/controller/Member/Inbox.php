<?php

namespace Budkit\Cms\Controller\Member;

use Budkit\Cms\Controller\Member;

class Inbox extends Member {

    public function index($format = 'html') {

        //var_dump($this->view);
        //$this->response->setContentType("pdf");
        $this->view->addData("action", ["title"=>"New Message","link"=>"/message/create", "class"=>"btn-primary"]);

        //throw new Exception("Something broke!");
        //var_dump($this->response);
        //echo "what about this";
        $this->view->setData("title", t("Inbox"));

        $this->view->addToBlock("main", "import://stream");

        $this->view->setLayout("member/dashboard");

    }


}