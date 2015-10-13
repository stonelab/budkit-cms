<?php

namespace Budkit\Cms\Controller\Admin;

use Budkit\Cms\Model\Media\Content;
use Budkit\Cms\Controller\Admin;

class Pages extends Admin {


    public function index($format = 'html', $id="") {

        //checks for a higher permission here, because there are links
        //to admin level actions
        $this->checkPermission("execute");

        //echo "Browsing in {$format} format";

        //2. load the page;
        $page = $this->application->createInstance( Content::class );
        $page = $page->defineValueGroup("page");
        $pages = $page->getAllMedia("page"); //gets a list of all pages;


        $this->view->addData("action", ["title"=>"Add Page","link"=>"/page/create", "class"=>"btn-primary"]);

        // echo "Pages admin";
        $this->view->setData("title", t("Pages"));
        $this->view->setData("pages", $pages);
        $this->view->addToBlock("main", "import://pages/list");
        $this->view->setLayout("member/dashboard");

    }
}