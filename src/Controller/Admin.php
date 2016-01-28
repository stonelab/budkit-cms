<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;


class Admin extends Controller {


    public function index($format="html") {
        //echo "Browsing in {$format} format";


        //echo "Browsing in {$format} format";
        $this->view->setData("title", "Dashboard");


      //Sending an email;
            try{

                $this->application->mailer
                ->compose("Test message default why is this not being sent?", "livingstonefultang@gmail.com")
                ->setSubject("This is a subject")
                ->send();

            }catch (\Exception $e){
                $this->response->addAlert(t("We were unable to send out a verification email."), "error");
                $this->application->dispatcher->returnToReferrer();
            }

        //We can add content to Block or just import more content;
        //$this->view->addToBlock("main", "This content");
        $this->view->addToBlock("main", "import://admin/console/widgets");

        $this->view->setLayout("member/dashboard");

    }

}