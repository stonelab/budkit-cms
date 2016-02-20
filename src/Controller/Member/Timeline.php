<?php

namespace Budkit\Cms\Controller\Member;

use Budkit\Cms\Controller\Member;

class Timeline extends Posts {



    public function map($format = 'html'){

        $this->view->setData("title", "Map" );

        //add the single stream

        $this->view->setData("sbstate", "minimized"); //the state of the sidebar
        $this->view->setData("csrftoken", $this->application->session->getCSRFToken());

        $this->view->addToBlock("main", 'import://posts/post-map');
        $this->view->setLayout('posts/post-dashboard');

    }

}