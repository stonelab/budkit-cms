<?php

namespace Budkit\Cms\Model;

use Budkit\Datastore\Database;
use Budkit\Datastore\Model\Entity;
use Budkit\Datastore\Model\Graph;
use Budkit\Event\Event;
use Budkit\Event\Observer;

class Story
{

    protected $database;
    protected $graph;

    public function __construct(Database $database, Observer $observer, User $user)
    {
        $this->database = $database;
        $this->observer = $observer;
        $this->user     = $user;
        $this->graph = new Graph;
    }

    /**
     * Every story is an edge in a graph whose id corresponds to the verb;
     *
     * Rules
     * -----
     * 1. Every story must have a single subject e.g the user performing the action (verb)
     * 2. Every story must have a single object e.g the object on which the action (verb) is performed
     * 3. Every story must have a single present past tensed verb e.g "posted", "created", "captured"
     *
     * @param $subject
     * @param $verb
     * @param $object
     * @param bool $direction
     * @return Graph
     */
    public function create(Entity $subject, $verb, Entity $object, $direction = true, Graph $graph = null)
    {

        $onNewStory = new Event('Story.onNewStory', $this, $object);
        $this->observer->trigger( $onNewStory ); //Parse the Node;


        $this->graph = ($graph) ? $graph : $this->getGraph();

        //Create subject node;
        $head = $this->graph->createNode($subject->getObjectURI(), []);
        $tail = $this->graph->createNode($object->getObjectURI(), []);

        //This is the story
        $this->graph->addEdge($head, $tail, $verb, $direction);


        return $this->saveGraph($this->graph);

    }


    public function saveGraph(Graph $graph)
    {

        $edges = $graph->getEdgeSet();
        $values = [];
        $table = $this->database->identifiers("?objects_edges", TRUE, NULL, FALSE);
        $query = "REPLACE INTO " . $table . " (edge_head_object, edge_tail_object, edge_name, edge_weight) VALUES ";

        if (!empty($edges)) {

            foreach ($edges as $edge) {

                $values[] = "('" . implode("', '", [
                        $edge->getHead()->getId(),
                        $edge->getTail()->getId(),
                        $edge->getName(),
                        $edge->getWeight()
                    ]) . "')";
            }

            $query .= implode(', ', $values). ";";

            if (!$this->database->query( $query , true )) {

                return false;
            }
        }

        $onGraphUpdate =  new Event('Story.onGraphUpdate', $this, $graph);
        $this->observer->trigger( $onGraphUpdate ); //Parse the Node;

        return $graph;
    }


    /**
     * Returns a story graph;
     *
     * @param null $verb
     * @param bool $directed
     * @param null $condition
     * @return bool|Graph
     * @throws Exception
     */
    public function get($verb = null, $directed = false, $conditional = null)
    {
        $this->graph = ($this->graph) ? new Graph : $this->getGraph();

        $this->database->startTransaction();
        $this->database->query("SET @sql = NULL;");
        $this->database->query("SET SESSION group_concat_max_len=5000000000;");
        //Now find all the property fields
        $this->database->query(
            "SELECT
              GROUP_CONCAT(DISTINCT
                CONCAT(
                  'MAX(IF(d.property_name =''', d.property_name, ''', d.value_data, NULL)) AS ', d.property_name , '\n'
                  )
              ) INTO @sql
            FROM {$this->database->replacePrefix("`?stories`")} AS d;"
        );


        //Mariadb does not support ANY_VALUE
        //$this->database->query("SET @sql = CONCAT('SELECT ', IFNULL( CONCAT(@sql, ','), '' ) ,' ANY_VALUE(d.edge_head_object) AS edge_head_object, ANY_VALUE(d.edge_tail_object) AS edge_tail_object, ANY_VALUE(d.edge_name) AS edge_name, ANY_VALUE(d.object_uri) AS object_uri, d.object_id, d.object_created_on  FROM {$this->database->replacePrefix('`?stories`')} AS d {$conditional} GROUP BY d.object_id ORDER BY d.object_created_on DESC');");
        $this->database->query("SET @sql = CONCAT('SELECT ', IFNULL( CONCAT(@sql, ','), '' ) ,' d.edge_head_object AS edge_head_object, d.edge_tail_object AS edge_tail_object, d.edge_name AS edge_name, d.object_uri AS object_uri, d.object_id, d.object_created_on  FROM {$this->database->replacePrefix('`?stories`')} AS d {$conditional} GROUP BY d.object_id ORDER BY d.object_created_on DESC');");
        $this->database->query("PREPARE stmt FROM @sql;");

        if (!$this->database->commitTransaction()) {
            throw new Exception("Could not load stories from the database");
            return false;
        }


        $results = $this->database->prepare("EXECUTE stmt;")->execute();
        $last = null;

        while($story = $results->fetchAssoc()){

            //Create subject node;
            $head = $this->graph->createNode($story['edge_head_object'], []);
            $tail = $this->graph->createNode($story['edge_tail_object'], $story);

            //This is the story
            $story  = $this->graph->addEdge($head, $tail, $story['edge_name'], true);
            $author = $this->user->loadObjectByURI($head->getId(),
                ["user_first_name","user_last_name","user_name_id","user_photo"]
            );


            //hide user password
            $handler = new Event("Story.onPrepareStory", $this, $story);
            $handler->setResult( $this->graph );


            //Get users to work on the story;
            $this->observer->trigger( $handler ); //Parse the Node;
			

            $this->graph = $handler->getResult();
			//Post prepare processing;
			//@TODO if  there is no defined stream_item_type remove from the storyboard;
            if(!isset($story['story_type'])){
                $this->graph->removeEdgeWithId( $story->getId() );
            }

            $profile = $author->getPropertyData();
            $profile['user_id'] = $author->getObjectId();


            $story->addData("story_author", $profile );
            $last = $story;


            //@TODO group items
            //1. If its the same user posting the same type of post, don't show person;
            //2. Stream hide person;
        }

        return $this->graph;
    }


    public function getGraph()
    {

        return $this->graph;

    }

    /**
     * Returns a stories graph containing both a subject and an object
     *
     * @param $subject the head node
     * @param $object the tail node
     * @param null $verb (optional) the edge name id
     * @param bool $direction (optional) if true checks for bi-directionality.
     * @return Graph
     */
    public function getBySubjectAndObject($subject, $object, $verb = null, $direction = false)
    {

        $conditions = [
            "d.edge_head_object" => $this->database->quote($subject),
            "d.edge_tail_object" => $this->database->quote($object),
        ];
        if($verb){
            $conditions["d.edge_name"] = $verb ;
        }
        //set the conditionals;
        $database = $this->database->where($conditions);

        return $this->get(null, false, $database->getConditionals());
    }

    /**
     * Returns a stories graph containing stories involving just a subject
     *
     * @param $subject the head node
     * @param null $verb optional edge name id
     * @return Graph
     */
    public function getBySubject($subject, $verb = null)
    {
        $conditions = ["d.edge_head_object" => $this->database->quote($subject)];
        if($verb){
            $conditions["d.edge_name"] = $verb ;
        }
        //set the conditionals;
        $database = $this->database->where($conditions);

        return $this->get(null, false, $database->getConditionals());
    }

    /**
     * Returns a stories graph containing stories involving just an object
     *
     * @param $object the tail node
     * @param null $verb optional edge name id
     *
     * @return Graph
     */
    public function getByObject($object, $verb = null)
    {
        $conditions = ["d.edge_tail_object" => $this->database->quote($subject)];
        if($verb){
            $conditions["d.edge_name"] = $verb ;
        }
        //set the conditionals;
        $database = $this->database->where($conditions);

        return $this->get(null, false, $database->getConditionals());
    }


    /**
     * Returns a stories graph containing stories of a particular taxon
     *
     * @param $taxon
     * @param null $verb
     */
    public function getByTaxon($taxon, $verb = null){}

    /**
     * Returns a stories graph contain stories where a specific use is mentioned
     * in the media_content field only
     *
     * @param $user
     * @param null $verb
     * @return bool|Graph
     * @throws Exception
     */
    public function getByUserMentionInContent($user, $verb=null){

        //set the conditionals;
        $database = $this->database->like("d.value_data", "@".$user->getPropertyValue("user_name_id"));

        return $this->get(null, false, $database->getConditionals());

    }


    /**
     *
     * Returns a stories graph with objects containing an attachment object
     *
     * @param null $ofType
     * @param null $verb
     */
    public function getWithAttachments($ofType = null, $verb=null){}


}