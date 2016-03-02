<?php

namespace Budkit\Cms\Controller\Member\Timeline;

use Budkit\Cms\Controller\Member;

class Stream extends Member\Timeline {


    public function manage($format = 'html')
    {
        echo "Display all timeline filters and show add more filters";


    }


    public function mentions($format = 'html'){
        //echo $type;

        parent::index($format);
    }



    public function execute($name, $format = 'html') {

        //echo func_num_args();
        //echo "Reading {$name} in {$format} format {}";

        $this->view->setData("filter", $name);

        return $this->index($format);

    }

}