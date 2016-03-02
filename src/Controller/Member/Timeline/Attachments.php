<?php

namespace Budkit\Cms\Controller\Member\Timeline;


class Attachments extends Stream {


    public function index($type='', $format = 'html')
    {
        echo $type;

        parent::index($format);
    }

}