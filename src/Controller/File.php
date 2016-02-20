<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;
use Budkit\Cms\Model\Media\Attachment;

class File extends Controller {


    public function read($uri, $format = 'html', $size="100x100") {
        //echo "Reading {$uri} , {$size} in {$format} format";

        $attachment = $this->application->createInstance( Attachment::class );

        if(empty($uri)){
            throw new \Exception("Requested file not found");
            return false;
        }


        //Load the file;
        $file = $attachment->loadObjectByURI( $uri );

        if( $file->getObjectType() !== "attachment" ){
            throw new \Exception("The requested item is not an file");
            return false;
        }

        if ($file->getObjectType() !== "attachment")
            return false; //we only deal with attachments, let others deal withit

        $fileId   = $file->getObjectType();
        $filePath = PATH_DATA. base64_decode( $file->getPropertyValue("attachment_src") );
        $contentType = $file->getPropertyValue("attachment_type");

        $attachment->place($fileId, $filePath, $contentType, !empty($size) ? ['resize'=>explode("x", $size)] : []);

    }


    public function placeholder($format = 'html', $size="100x100"){

        //The method is the object unique ID

        $attachment = $this->application->createInstance( Attachment::class );

        //$object     = $entity->loadObjectByURI( $resourceId ); //@todo Validate URI
        //$objectType = $object->getPropertyValue("objectType");
        //Loads all the system objects;
        return $attachment->place(NULL, NULL, "image/png",  !empty($size) ? ['resize'=>explode("x", $size)] : []);
    }



    public function edit($id = 'new', $format = 'html') {
        echo "Editing {$id} in {$format} format";
    }

    public function upload($format ="html", $key = "") {


        $key = empty($key)? "attachment" : $key;

        //Check that form was submitted with the POST method
        if ($this->application->input->methodIs("post") ) { //param 0 is the name of the input file field

            //@TODO Need to check against csf

            $message = "The attachment has been saved successfully";
            $messageType = "success";

            $attachment = $this->application->createInstance( Attachment::class );

            //if no user is signed in, upload to temp;
            //$responses = []

            $files = $this->application->input->data("files");

//            $this->set("uploaded", $attachmentfile );
//            $this->set("uploaded-name", (string) $filename );

            if($this->user->getCurrentUser()->isAuthenticated()){
                $attachment->setOwnerNameId($this->user->getCurrentUser()->getPropertyValue("user_name_id"));
            }

            //Now store the users photo to the database;
           if( !( $saved = $attachment->save($files[$key]) ) ){
                throw new \Exception("Could not upload the requested file");
               return false;
           }
//
            $this->view->setData("whynojsonla","maaaaaa");
            $this->view->setData("uploaded", $saved ); //If we have succesfully uploaded the attachment display

            print_r(json_encode($saved));

            return $saved;

        }else {

            //$this->output->setResponseCode(HTTP_BAD_REQUEST);

            throw new \Exception("The file upload action  accepts only POST data", "HTTP BAD REQUEST", "error");
            return false;
        }

    }

    public function delete() {
        echo "Delete...";
    }

    public function replace() {
        echo "Replacing...";
    }

    public function options() {
        echo "Options...";
    }
}