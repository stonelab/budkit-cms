<?php

namespace Budkit\Cms\Controller\Member\Timeline;

use Budkit\Cms\Controller\Member;
use Budkit\Cms\Model\Story;
use ArrayObject;

class Stream extends Member\Timeline {


    public function manage($format = 'html')
    {
        //echo "Display all timeline filters and show add more filters";

        $this->view->setLayout("posts/post-labels");

    }


    public function mentions($format = 'html'){
        //echo $type;

        $this->view->setData("title", "@Mentions");

        $story = $this->application->createInstance( Story::class );
        $graph = $story->getByUserMentionInContent( $this->user->getCurrentUser() );

        //die;

        $this->view->setData("stories", getArrayObjectAsArray( new ArrayObject( $graph->getEdgeSet() ) ) );


        $this->timeline();

        //parent::index($format);
    }



    public function execute($name, $format = 'html') {

        //echo func_num_args();
        //echo "Reading {$name} in {$format} format {}";

        $this->view->setData("filter", $name);

        return $this->index($format);

    }

}