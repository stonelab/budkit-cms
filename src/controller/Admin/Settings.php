<?php

namespace Budkit\Cms\Controller\Admin;

use Budkit\Cms\Model\Authority;
use Budkit\Cms\Controller\Admin;

class Settings extends Admin {


    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";

        //3. Get the authorities list
        $authorityModel = $this->application->createInstance( Authority::class );

        $authorities = $authorityModel->getAuthorities();

        //print_R($authorities);

        //4. Set Properties
        $this->view->setData("authorities", $authorities);

       // echo "Pages admin";
        $this->view->setData("title", t("Settings » Site"));

        $this->view->addToBlock("main", "import://admin/settings/configuration");
        //$this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/dashboard");

    }

}