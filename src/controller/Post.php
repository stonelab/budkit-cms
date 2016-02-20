<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;
use Budkit\Cms\Model\Media\Content;
use Budkit\Cms\Model\Story;
use Budkit\Helper\Date;
use Budkit\Helper\Time;
use ArrayObject;

class Post extends Controller {


    public function index($format = 'html') {

        $this->view->setData("title", "Timeline");

        $story = $this->application->createInstance( Story::class );
        $graph = $story->get();

        $this->view->setData("stories", getArrayObjectAsArray( new ArrayObject( $graph->getEdgeSet() ) ) );


        $this->timeline();

    }


    public function read($id, $format = 'html') {

        //We are going to add a single Item;
        //$this->index();

        //Change the title
        $title = "Reading {$id} in {$format} format";

        $this->view->setData("title", $title );

        //add the single stream

        //$this->view->setData("object_uri", $uri);
        $this->view->setData("csrftoken", $this->application->session->getCSRFToken());
        $this->view->addToBlock("main", 'import://posts/post-single');
        $this->view->setLayout('posts/post-dashboard');

    }

    public function edit($id = 'new', $format = 'html') {
        echo "Editing {$id} in {$format} format";
    }

    public function put($format = 'html') {

        //1. check this uer has permission to execute /page/create
        $this->checkPermission("execute");

        //2. are we patching or updating an existing?
        $input = $this->application->input;

        if ($input->methodIs("post")) { //because we are updating;


            //3. load the page;
            $content = $this->application->createInstance(Content::class);

            $_content = $content->getPropertyModel();

            // print_R($_POST);
            foreach ($_content as $attribute => $definition):
                $value = $input->getString($attribute, "", "post");
                $content->setPropertyValue($attribute,  $value);
            endforeach;

            //Allow some HTML in media content;
            $mediaContent = $input->getFormattedString("media_content", "", "post", true);

            $content->setPropertyValue("media_content", $mediaContent);

            //@TODO determine the user has permission to post;
            $content->setPropertyValue("media_owner", $this->user->getCurrentUser()->getPropertyValue("user_name_id"));
            $content->setPropertyValue("media_published", Time::stamp());

            if (!$content->saveObject(null, "media")) {
                //There is a problem! the error will be in $this->getError();
                $this->response->addAlert("Your post could not be submitted an error occurred", "error");
                //return false;
            }else{

                $this->response->addAlert("Your post was submitted successfully", "success");

                //Now create a new user story
                $post  = $content->loadObjectByURI( $content->getLastSavedObjectURI() );
                $story = $this->application->createInstance( Story::class );

                if( !$story->create( $this->user->getCurrentUser(), "posted", $post ) ){
                    $this->response->addAlert("Your post was submitted, but an error occurred whilst adding it to the timeline", "warning");
                }
            }
        }
        $this->application->dispatcher->returnToReferrer();
    }

    public function delete($uri, $format = 'html') {
        echo "Delete...";
    }

    public function create($uri, $format = 'html') {

        $this->view->setData("editor", "post");
        $this->view->setData("title", "Create New Post");

        $this->view->setLayout("editor");
    }

    public function update($uri, $format = 'html') {
        echo "Updating...";
    }

    public function replace($uri, $format = 'html') {
        echo "Replacing...";
    }

    public function options($uri, $format = 'html') {
        echo "Options...";
    }


    private function timeline(){


        //$this->view->addData("action", ["title"=>"Map","link"=>"/member/timeline/map", "class"=>"btn-primary"]);

        //$this->view->setData("object_uri", $uri);
        $this->view->setData("csrftoken", $this->application->session->getCSRFToken());
        $this->view->addToBlock("main", 'import://posts/post-inbox');
        $this->view->setLayout('posts/post-dashboard');

    }
}