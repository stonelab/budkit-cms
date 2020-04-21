<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Controller;
use Budkit\Cms\Helper\ErrorNotFoundException;
use Budkit\Cms\Model\Media\Content;
use Budkit\Cms\Model\Media\MediaLink;
use Budkit\Cms\Model\Story;
use Budkit\Event\Event;
use Budkit\Helper\Date;
use Budkit\Helper\Time;
use ArrayObject;
use Parsedown;

class Post extends Controller {


    public function index($format = 'html') {

        $this->view->setData("title", "Timeline");

        $story = $this->application->createInstance( Story::class );
        $graph = $story->get();

        $this->view->setData("stories", getArrayObjectAsArray(  $graph->getEdgeSet()  ) );

        $this->timeline();

    }

    public function read($id,  $format = 'html') {

        //We are going to add a single Item;
        //$this->index();

//        $args = func_get_args();
//
//        $route = $this->application->router->getMatchedRoute();
//
//        print_R($args);
//
//        print_R($route);
//
//        echo $format;
//
//        die;

        if (empty($id)){
            //Checks for a homepage in the settings
            throw new ErrorNotFoundException('The requested post does not exists');
            return false;
        }

            //2. load the page;
            $post = $this->application->createInstance( Content::class );
            $post = $post->defineValueGroup("media");
            $post = $post->getMedia(null, $id);

            //throw a not found exception if the page id does not exists
            if (!isset($post["items"]) || count($post["items"]) < 1) {
                throw new ErrorNotFoundException("The requested page does not exist");
                return false;
            }

            $read = reset($post["items"]); //first element;

            //If this is the homepage, lets add some more data;
            //set homepage data;
            $onReadPost = new Event('Post.onPost', $this);
            $onReadPost->setResult($read);
            $this->observer->trigger( $onReadPost ); //Parse the Node;

            $read = $onReadPost->getResult();

            //lets fix the content;
//            if(isset($read["media_content"])) {
//
//
//                $read["media_content"] = Parsedown::instance()
//                    // ->setBreaksEnabled(true) # enables automatic line breaks
//                    ->text($read["media_content"]);
//
//            }

            // 1. load the page;
            $template =  ( isset($read["media_template"]) && !empty($read["media_template"]) ) ? $read["media_template"] : null; //determine page template from


            //show a page or load custom page template
            $this->view->setData("title", (isset($read["media_template"]) && !empty($read["media_title"]) ? $read["media_title"] : Time::difference( strtotime($read['object_created_on']) )  ));
            $this->view->setData("reading", $read);


        //If we are using a custom template;
        if (!empty($template)) {

            //Trigger Layout.onLoad.page.template.definitions
            $templateDefinitions = [];
            $loadPostTemplates = new Event('Layout.onLoad.post.template.definitions', $this);
            $loadPostTemplates->setResult($templateDefinitions);

            $this->observer->trigger($loadPostTemplates);

            $definedTemplates = $loadPostTemplates->getResult();

            foreach ($definedTemplates as $_ => $def) {
                //add the look up paths to the layout so the view can be found
                if(!isset($def["name"]) || !isset($def["source"])) continue;
                if($def["name"] === $template) $this->view->appendLayoutSearchPath($def["source"]);
            }
        }


        $layout = empty($template) ? "posts/post-single" :  $template;


        //$this->view->setData("object_uri", $uri);
        $this->view->setData("csrftoken", $this->application->session->getCSRFToken());
        $this->view->addToBlock("main", 'import://'.$layout);
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


    protected function timeline(){


        //$this->view->addData("action", ["title"=>"Map","link"=>"/member/timeline/map", "class"=>"btn-primary"]);

        //$this->view->setData("object_uri", $uri);
        $this->view->setData("csrftoken", $this->application->session->getCSRFToken());
        $this->view->addToBlock("main", 'import://posts/post-inbox');
        $this->view->setLayout('posts/post-dashboard');

    }
}