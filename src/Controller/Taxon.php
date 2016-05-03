<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 03/04/2016
 * Time: 11:12
 */

namespace Budkit\Cms\Controller;


class Taxon extends Post{

    public function addTo(){}
    public function removeFrom(){}

    public function create($uri, $format = 'html'){}
    public function delete($uri, $format='html'){}
    public function bookmark(){}

    public function view($name, $format = 'html') {

        //echo func_num_args();
        //echo "Reading {$name} in {$format} format {}";

        $this->view->setData("taxon", $name);

        return $this->index($format);

    }

    public function taxa($format = 'html')
    {
        //echo "Display all timeline filters and show add more filters";

        return $this->view->setLayout("posts/post-labels");

    }

}