<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 14/09/15
 * Time: 22:50
 */

namespace Budkit\Cms\Model;


use Budkit\Cms\Model\Media\Collection;
use Budkit\Cms\Model\Media\Content;
use Budkit\Datastore\Database;
use Budkit\Dependency\Container;

class Page extends Content{



    public function __construct(Database $database, Collection $collection, Container $application, User $user) {

        $this->encryptor = $application->encrypt;
        $this->config = $application->config;
        $this->database =   $database;

        parent::__construct($database, $collection, $application, $user);

        //$this->definePropertyModel( $dataModel ); use this to set a new data models
        $this->defineValueGroup("page"); //Tell the system we are using a proxy table

        //Pagination
        $currentpage = $application->input->getInt("page", "1", "attribute");

        $this->setState("currentpage", (int) $currentpage);

    }




    /**
     * Returns a user datastore row
     * @todo User EAV model load
     * @return void
     */
    protected function load( $objectId ) {

    }

    /**
     * Deletes a user record from the datastore
     * @todo User delete
     * @return void
     */
    public function delete() {

    }


}