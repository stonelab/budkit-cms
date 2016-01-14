<?php

namespace Budkit\Cms\Controller\Member\Timeline;

use Budkit\Cms\Controller\Member;

class Filters extends Member\Timeline {

    public function read($name, $format = 'html') {
        //echo "Reading {$name} in {$format} format";

        $this->view->setData("filter", $name);

        return $this->index($format);

    }

}