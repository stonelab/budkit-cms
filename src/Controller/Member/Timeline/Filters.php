<?php

namespace Budkit\Cms\Controller\Member\Timeline;

use Budkit\Cms\Controller\Member;

class Filters extends Member\Timeline {


    public function manage($format = 'html')
    {
        echo "Display all timeline filters and show add more filters";


    }


    public function execute($username, $name, $format = 'html') {

        //echo func_num_args();
        //echo "Reading {$name} in {$format} format {}";

        $this->view->setData("filter", $name);

        return $this->index($format);

    }

}