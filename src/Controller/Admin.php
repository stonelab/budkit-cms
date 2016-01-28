<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;


class Admin extends Controller {


    public function index($format="html") {
        //echo "Browsing in {$format} format";


        //echo "Browsing in {$format} format";
        $this->view->setData("title", "Dashboard");


        //Sending an email;
        $this->application->mailer->compose("Test message", "livingstonefultang@gmail.com")->send();

        //We can add content to Block or just import more content;
        //$this->view->addToBlock("main", "This content");
        $this->view->addToBlock("main", "import://admin/console/widgets");

        $this->view->setLayout("member/dashboard");

    }

}