<?php

namespace Budkit\Cms\Controller\Admin\Settings;

use Budkit\Cms\Controller\Admin\Settings;
use Budkit\Cms\Model\Authority;


class Permissions extends Settings {

    /**
     * Displays a list of network authority groups
     * @todo Implement the ability to modify groups
     * @param string $edit
     * @return void
     */
//    public function addRule() {
//        //1. Load the model
//        $authority = $this->load->model("authority");
//        //2. If we are editing the authority, save
//        if ($this->input->methodIs("post")):
//            if (!$authority->storePermissions()) {
//                $errors = $this->getErrorString();
//                $this->alert($errors, null, "error");
//            } else {
//                //Succesffully added
//                $this->alert(_("Permisison rule has been added successfully"), "", "success");
//            }
//        endif;
//        //Redirect back to the settings page
//        $this->redirect($this->output->link("/settings/system/permissions"));
//    }

    public function addRule(){

    }


    public function updatedRule()
    {}

    public function updateAuthorities(){}

    public function deleteRule(){}

    public function deleteAuthority(){}

    public function authorities($edit = "") {

        $view = $this->load->view('system');
        $params = $this->getRequestArgs();

        //1. Load the model
        $authority = $this->load->model("authority");

        //2. If we are editing the authority, save
        if ($this->input->methodIs("post")):
            if (!$authority->store($edit, $params)) {
                $errors = $this->getErrorString();
                $this->alert($errors, null, "error");
            } $this->alert(_("Changes have been saved successfully"), "", "success");
            $this->redirect($this->output->link("/settings/system/permissions/authorities"));
        endif;

        //3. Get the authorities list
        $authorities = $authority->getAuthorities();

        //print_r($authorities);
        //4. Set Properties
        $this->set("authorities", $authorities);
        //5. The layout
        $view->form('system/authorities', 'Authorities');
    }

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

