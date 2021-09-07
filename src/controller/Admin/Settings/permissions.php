<?php

namespace Budkit\Cms\Controller\Admin\Settings;

use Budkit\Cms\Controller\Admin\Settings;
use Budkit\Cms\Model\Authority;


class Permissions extends Settings
{


    public function addRule($format = 'html')
    {

        //1. Load the model
        $authority = $this->application->createInstance(Authority::class);

        //2. If we are editing the authority, save
        if ($this->application->input->methodIs("post")):

            if (!$authority->storePermissions()) {

                $this->response->addAlert(_("Could not save permissions"), "error");
            } else {
                //Succesffully added
                $this->response->addAlert(_("Permisison rule has been added successfully"), "success");
            }

        endif;

        //Report on state saved
        $this->application->dispatcher->redirect($this->application->input->getReferer(), HTTP_FOUND, null);

        return true;

    }


    public function addAuthority($format = 'html')
    {

        if ($this->application->input->methodIs("post")):

            $authorityTitle = $this->application->input->getString("authority-title", "", "post");
            $authorityParent = $this->application->input->getInt("authority-parent", "", "post");
            $authorityId = $this->application->input->getInt("authority-id", "", "post");

            $authorityDescription = $this->application->input->getString("authority-description", "", "post");

            $authorityName = strtoupper(str_replace(array(" ", "(", ")", "-", "&", "%", ",", "#"), "", $authorityTitle));

//        $authorityAreaTitle         = $this->input->getArray("area-title", array() );
//        $authorityAreaURI           = $this->input->getArray("area-uri", array() );
//        $authorityAreaAction        = $this->input->getArray("area-action", array() );
//        $authorityAreaPermission    = $this->input->getArray("area-permission", array() );
//
//        $authorityAreaName          = strtoupper(str_replace(array(" ", "(", ")", "-", "&", "%", ",", "#"), "", $authorityAreaTitle));
//

            $aData = array(
                "authority_id" => $authorityId,
                "authority_name" => $authorityName,
                "authority_title" => $authorityTitle,
                "authority_parent_id" => empty($authorityParent) ? 1 : (int)$authorityParent,
                "authority_description" => $authorityDescription
            );

            //1. Load the model
            $authority = $this->application->createInstance(Authority::class);

            //2. If we are editing the authority, save

            if (empty($aData['authority_name']) || empty($aData['authority_title']) || !$authority->store($aData)) {

                $this->response->addAlert(_("Could not save authority"), "error");
            } else {
                //Succesffully added
                $this->response->addAlert(_("Authority group has been added successfully"), "success");
            }

        endif;

        //Report on state saved
        $this->application->dispatcher->redirect($this->application->input->getReferer(), HTTP_FOUND, null);

        return true;

    }


    public function getAuthorityMembers($format = 'html')
    {
    }


    public function updatedRule($format = 'html')
    {
    }

    public function updateAuthority($format = 'html')
    {
    }

    public function deleteRule($format = 'html')
    {
    }

    public function deleteAuthority($format = 'html')
    {
    }


    public function index($format = 'html', $id = "")
    {
        //echo "Browsing in {$format} format";

        //3. Get the authorities list
        $authorityModel = $this->application->createInstance(Authority::class);
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

