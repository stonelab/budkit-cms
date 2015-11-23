<?php

namespace Budkit\Cms\Controller\Admin;

use Budkit\Cms\Model\Authority;
use Budkit\Cms\Controller\Admin;
use Budkit\Cms\Model\Options;

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
        $this->view->setData("title", t("Configurations"));

        $this->view->addToBlock("main", "import://admin/settings/configuration");
        //$this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/dashboard");
    }

    /**
     * Saves configuraiton settings
     * @return boolean
     */
    public function save() {

        //do you have permission to execute admin
        $this->checkPermission("special", "/admin");

        $referer = $this->application->input->getReferer();
        //$options = $this->application->createInstance( Options::class );

        //Check that we have post data;
        if (!$this->application->input->methodIs("post")) {
            $this->response->addAlert("No configuration data recieved", 'error');
        }else {
            //Get the data;
            if (($data = $this->application->input->getArray("options", array(), "post")) == FALSE) {
                $this->response->addAlert("No input data recieved, Something went wrong", 'error');
            }else{

                $namespace = $this->application->input->getString("options_namespace", "", "post");
                //print_R($data);
                $this->application->config->mergeParams($namespace, $data);

                if (!$this->application->config->saveParams()) {
                    $this->response->addAlert('Something went wrong, Did not save the parameters', 'error');
                }else{
                    $this->response->addAlert("Your configuration settings have now been saved",  "success");
                }
            }
        }
        //Report on state saved
        $this->application->dispatcher->redirect($referer, HTTP_FOUND, null, $this->response->getAlerts());

        return true;
    }

}