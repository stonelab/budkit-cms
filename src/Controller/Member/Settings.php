<?php

namespace Budkit\Cms\Controller\Member;

use Budkit\Cms\Controller\Member;
use Budkit\Cms\Model\Media\Attachment;
use Budkit\Validation\Sanitize;

class Settings extends Member {


    public function index( $group="", $format = 'html') {
        //echo "Browsing in {$format} format";
        if(!empty($group) && !in_array($group, ["profile","notifications"])){
            throw new \Exception( t("The requested configuration group does not exists") );
            return false;
        }

        $group = empty($group)? "account" : $group ;


        $profile  = $this->user->getCurrentUser();

        $data = $profile->getPropertyData();

        unset($data['user_password']);
        //unset($data['user_verification']);
        unset($data['user_verified']);


        //print_r($data);

        //overload user var with profile data
        $this->view->setData("account", $data); //Sets the profile data;

       // echo "Pages admin";
        $this->view->setData("title", t("Settings"));

        //$this->view->setData("name", "Livingstone");
        $this->view->addToBlock("main", 'import://member/settings/'.$group);
        $this->view->setLayout("member/dashboard");

    }


    public function update(){

        $current = $this->user->getCurrentUser();
        $input   = $this->application->input;

        if(!$current->isAuthenticated()) {
            $this->response->addAlert("No authenticated user session available", 'error');
        }else {
            //Check that we have post data;
            if (!$input->methodIs("post")) {
                $this->response->addAlert("No configuration data received", 'error');
            } else {
                //Get the data;
                if (($data = $input->data("post") ) == FALSE ) {
                    $this->response->addAlert("No input data received, Something went wrong", 'error');
                } else {


                    //Cleanup everything;
                    $sanitized = new Sanitize($data, FILTER_DEFAULT, [], $this->application->validate);
                    $update    = $sanitized->getData();

                    //if we have a user profile picture;
                    $files = $this->application->input->data("files");


                    if(!empty($files['user_photo']['name']) && isset($files['user_photo']) && !empty($files['user_photo'])){

                        //Save cover photo;
                        $attachment = $this->application->createInstance( Attachment::class );
                        $attachment->setOwnerNameId( $current->getPropertyValue("user_name_id") );

                        if( !( $saved = $attachment->save($files['user_photo']) ) ){
                            throw new \Exception("Could not upload the requested file");
                            return false;
                        }

                        $update['user_photo'] = $attachment->getLastSavedObjectUri();

                    }

                    //Store the user data
                    if (!$current->update( $current->getPropertyValue("user_name_id"), $update)) {
                        $this->response->addAlert('Something went wrong, Could not update your preferences', 'error');
                    } else {
                        $this->response->addAlert("Your configuration settings have now been saved", "success");
                    }
                }
            }
        }
        //Report on state saved
        $referer = $this->application->input->getReferer();
        $this->application->dispatcher->redirect($referer, HTTP_FOUND, null);

        return true;
    }

}