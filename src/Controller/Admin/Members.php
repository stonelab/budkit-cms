<?php

namespace Budkit\Cms\Controller\Admin;

use Budkit\Cms\Model\Authority;
use Budkit\Cms\Controller\Admin;
use Budkit\Cms\Model\User;

class Members extends Admin {


    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";


        //$this->view->addData("action", ["title"=>"Add Member","link"=>"/member/create", "class"=>"btn-primary"]);

       // echo "Pages admin";
        $this->view->setData("title", t("Members"));

        $user = $this->application->createInstance( User::class );

        //$page = $page->defineValueGroup("page");
        $members = $user->getObjectsList("user", ["user_name_id","user_photo","user_first_name","user_last_name","user_verified"])->fetchAll(); //gets a list of all pages;

        $this->view->setData("members", $members);

         //Pagination only exists if the size of available pages is greater than 1
        //print_r($members); die;

        $pagination = $user->getPagination();
        if($pagination) {
            $this->view->setData("pagination", $pagination);
        }

        $this->view->addToBlock("main", "import://member/member-list");
        $this->view->setLayout("member/dashboard");
    }


    public function moderate($format){

        $this->checkPermission("special");

        echo "Moderating a particular user";
    }

}