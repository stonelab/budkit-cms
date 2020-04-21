<?php

namespace Budkit\Cms\Controller\Member\Timeline;

use Budkit\Cms\Controller\Member;
use Budkit\Cms\Model\Story;
use ArrayObject;

class Stream extends Member\Timeline {
    

    public function mentions($format = 'html'){
        //echo $type;

        $this->view->setData("title", "@Mentions");

        $story = $this->application->createInstance( Story::class );
        $graph = $story->getByUserMentionInContent( $this->user->getCurrentUser() );

        //die;

        $this->view->setData("stories", getArrayObjectAsArray( $graph->getEdgeSet() ) );


        $this->timeline();

        //parent::index($format);
    }


}