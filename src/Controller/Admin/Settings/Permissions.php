<?php

namespace Budkit\Cms\Controller\Admin\Settings;

use Budkit\Cms\Controller\Admin\Settings;
use Budkit\Cms\Model\Authority;


class Permissions extends Settings {


    public function addRule($format = 'html'){

        //1. Load the model
        $authority = $this->application->createInstance( Authority::class );

        //2. If we are editing the authority, save
        if ($this->application->input->methodIs("post")):

            if (!$authority->storePermissions()) {

                $this->response->addAlert( _("Could not save permissions"), "error");
            } else {
                //Succesffully added
                $this->response->addAlert(_("Permisison rule has been added successfully"),  "success");
            }

        endif;

        //Report on state saved
        $this->application->dispatcher->redirect($this->application->input->getReferer(), HTTP_FOUND, null);

        return true;

    }


    public function addAuthority($format = 'html'){}


    public function getAuthorityMembers($format = 'html'){}


    public function updatedRule($format = 'html'){}
    public function updateAuthority($format = 'html'){}

    public function deleteRule($format = 'html'){}
    public function deleteAuthority($format = 'html'){}


    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";

        //3. Get the authorities list
        $authorityModel = $this->application->createInstance( Authority::class );
        $authorities = $authorityModel->getAuthorities();

        //print_R($authorities);

        //4. Set Properties
        $this->view->setData("authorities", $authorities);

        // echo "Pages admin";
        $this->view->setData("title", t("Settings » Permissions"));

        $this->view->addToBlock("main", "import://admin/settings/permissions");
        //$this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/dashboard");

    }

}

