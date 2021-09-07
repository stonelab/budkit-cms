<?php

namespace Budkit\Cms\Model;
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 14/03/2016
 * Time: 16:39
 */

class Taxonomy {

    protected $database;
    protected $graph;

    public function __construct(Database $database, Observer $observer, User $user)
    {
        $this->database = $database;
        $this->observer = $observer;
        $this->user     = $user;
    }

    /**
     * Returns a list of all taxa owned by this user
     *
     * @param User $user
     */
    public function getUserTaxa(User $user){}


    /**
     * Returns a list of objects of a particular taxon
     *
     * @return array
     */
    public function getObjectsWhereTaxonIs(){}


    /**
     * Adds a unique object / taxon relationship
     *
     * @param $object_id
     * @param $tax_id
     */
    public function addObjectTaxon($object_id, $tax_id){

    }

    /**
     * Removes an object / taxon relationship
     *
     * @param $object_id
     * @param $tax_id
     */
    public function removeObjectTaxon($object_id, $tax_id){

    }

    /**
     * Adds a new taxon to the database
     *
     * @param array $taxon
     * @param User $owner
     */
    public function addTaxon(array $taxon, User $owner){}


    /**
     * Deletes a taxon from the database;
     *
     * @param $ax_id
     */
    public function deleteTaxon( $ax_id ){ }

}