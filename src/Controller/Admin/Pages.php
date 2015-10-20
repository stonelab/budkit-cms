<?php

namespace Budkit\Cms\Controller\Admin;

use Budkit\Cms\Controller\Admin;
use Budkit\Cms\Model\Page;

class Pages extends Admin {


    public function index($format = 'html', $page="") {

        // This is our new stuff
        $context = new \ZMQContext();

        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");

        $socket->send(json_encode([
            "topic"=>"/admin/pages",
            "message"=>"Admin page refreshed"
        ]));


        //checks for a higher permission here, because there are links
        //to admin level actions
        $this->checkPermission("execute");

        $page = $this->application->createInstance( Page::class );

        //$page = $page->defineValueGroup("page");
        $pages = $page->getAllMedia("page"); //gets a list of all pages;


        //print_r($page->getPagination());

        $this->view->addData("action", ["title"=>"Add Page","link"=>"/page/create", "class"=>"btn-primary"]);

        // echo "Pages admin";
        $this->view->setData("title", t("Pages"));
        $this->view->setData("pages", $pages);

        $pagination = $page->getPagination();

        if($pagination["total"] > 1) {
            $this->view->setData("pagination", $page->getPagination());
        }

        $this->view->addToBlock("main", "import://pages/page-list");
        $this->view->setLayout("member/dashboard");

    }
}