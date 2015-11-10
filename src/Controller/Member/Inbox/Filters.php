<?php

namespace Budkit\Cms\Controller\Member\Inbox;

use Budkit\Cms\Controller\Member;

class Filters extends Member\Inbox {

    public function read($name, $format = 'html') {
        echo "Reading {$name} in {$format} format";

        return $this->index($format);

    }

    public function edit($name = 'new', $format = 'html') {
        echo "Editing {$name} in {$format} format";

        return $this->index($format);
    }

    public function add() {
        echo "Adding...";

        return $this->index();
    }

    public function delete() {
        echo "Delete...";

        return $this->index();
    }

    public function create() {

        echo "creating";

        return $this->index();
    }

    public function update() {
        echo "Updating...";

        return $this->index();
    }

    public function replace() {
        echo "Replacing...";

        return $this->index();
    }

    public function options() {
        echo "Options...";

        return $this->index();
    }

}