<?php


namespace Budkit\Cms\Model\Media;

use Budkit\Cms\Model\User;
use Budkit\Datastore\Database;
use Budkit\Datastore\Model\Entity;
use Budkit\Dependency\Container;

/**
 * Media stream object model
 *
 * In its simplest form, an media consists of an actor, a verb, an an object, 
 * and a target. It tells the story of a person performing an action on or with 
 * an object -- "Geraldine posted a photo to her album" or "John shared a video". 
 * In most cases these components will be explicit, but they may also be implied.
 *
 * @category  Application
 * @package   Data Model
 * @license   http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version   1.0.0
 * @since     Jan 14, 2012 4:54:37 PM
 * @author    Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 * 
 */
class Content extends Entity {


    /**
     *
     * @var Collection
     */
    protected $collection;

    /**
     * Static array of default system verbs
     * @var array 
     */
    static $_verbs = array(
        "post"
    );

    static protected $_mediaTypes = array(
        "media", "attachments"
    );


    public function __construct(Database $database, Collection $collection, Container $container, User $user) {

        parent::__construct($database, $container);

        $this->collection   = $collection;
        $this->input        = $container->input;
        $this->user         = $user->getCurrentUser();

        //"label"=>"","datatype"=>"","charsize"=>"" , "default"=>"", "index"=>TRUE, "allowempty"=>FALSE
        $this->definePropertyModel(array(
            "media_published" => array("Published", "datetime", 50),
            "media_content" => array("Content", "varchar", 5000),
            "media_title" => array("Title", "mediumtext", 1000, NULL),
            "media_summary" => array("Summary", "mediumtext", 50, NULL),
            "media_comment_status" => array("Allow Comments", "tinyint", 1, 0), //*
            "media_parent" => array("Parent", "smallint", 10, 0), //*
            "media_generator" => array("Generator", "mediumtext", 100),
            "media_template" => array("Template", "mediumtext", 100),
            "media_provider" => array("Provider", "mediumtext", 100, "budkit"),
            "media_mentions" => array("Mentions", "varchar", 1000), //*
            "media_owner" => array("Owner", "varchar", 1000),
           // "media_verb" => array("Verb", "mediumtext", 20, "post"),
            "media_geotag_lat" => array("Geottag Latitude", "varchar", 1000),
            "media_geotag_long" => array("Geottag Longitude", "varchar", 1000), //*
            "media_attachments" => array("Object", "varchar", 1000),
            //"media_target" => array("Target", "varchar", 1000), //the object uri of the timeline stream;
            "media_permissions" => array("Permissions", "mediumtext", 50), //* //allow:{},deny:{}
        ), "media");

        $this->defineValueGroup("media");
        $this->setListOrderBy(array("o.object_updated_on"), "DESC");
    }

    /**
     * Gets a collection with a single item;
     * 
     * @param type $objectType
     * @param type $objectURI
     * @param type $objectId
     * @return type
     */
    public function getMedia($objectType = 'media', $objectURI = NULL, $objectId = NULL) {
        //An alias method for getall
        return $this->getAllMedia($objectType, $objectURI, $objectId);
    }

    /**
     * Returns all the published media stories
     * @return array An array of media stream objects see {@link Media\Collecion}
     */
    public function getAllMedia($objectType = 'media', $objectURI = NULL, $objectId = NULL) {

        //Get the object list of items which have no target for the timeline;
        //The timeline is for root objects only, any item with a target is the result of an interaction
        //For instance blah commented on itemtarget etc... and should be shown on a seperate activity feed
        $objects = $this->getMediaObjectsList($objectType, $objectURI, $objectId)->fetchAll();
        $items = array();

        //Parse the mediacollections;
        foreach ($objects as $i=>$object) {
            $object = $this->getOwner($object, $object['media_owner']);

            //If object is an attachment ?
            if($object['object_type']==="attachment"):
               $object['media_object'] = $object['object_uri']; //add to the collection
                if(empty($object['media_title'])):
                    $object['media_title'] = $object['attachment_name'];
                endif;
            endif;

            $object['media_comment_target'] = $object['object_uri'];
            $object['media_published'] = $object['object_created_on'];

            //CleanUp
//            foreach ($object as $key => $value):
//                $object[str_replace(array('media_', 'object_'), '', $key)] = $value;
//                unset($object[$key]);
//            endforeach;

            $items[] = $object;
        }

        //print_r($items);

        $mediacollections = new Collection();
        $mediacollections::set("items", $items); //update the collection
        $mediacollections::set("totalItems", count($items));

        $collection = $mediacollections::getArray();

        return $collection;
    }

    /**
     * Prepares and executes a database query for fetching media objects
     * @param interger $objectId
     * @param string $objectURI
     * @return object Database resultset
     */
    public function getMediaObjectsList($objectType = 'media', $objectURI = NULL, $objectId = NULL) {
        //Join Query
        //$objectType = 'media';

        $query = "SELECT o.object_id, o.object_uri, o.object_type, o.object_created_on, o.object_updated_on, o.object_status";
        //If we are querying for attributes
        $_properties = $this->getPropertyModel();
        $properties = array_keys((array) $_properties);

        $count = count($properties);
        if (!empty($properties) || $count < 1):
            //Loop through the attributes you need
            $i = 0;
            $query .= ",";
            foreach ($properties as $alias => $attribute):
                $alias = (is_int($alias)) ? $attribute : $alias;
                $query .= "\nMAX(IF(p.property_name = '{$attribute}', v.value_data, null)) AS {$alias}";
                if ($i + 1 < $count):
                    $query .= ",";
                    $i++;
                endif;
            endforeach;

            //Join the UserObjects Properties
            $_actorProperties = $this->user->getPropertyModel();
            $actorProperties = array_diff(array_keys($_actorProperties), array("user_password", "user_api_key", "user_email"));
            $count = count($actorProperties);
            if (!empty($actorProperties) || $count < 1):
                $query .= ","; //after the last media property   
                $i = 0;
                foreach ($actorProperties as $alias => $attribute):
                    $alias = (is_int($alias)) ? $attribute : $alias;
                    $query .= "\nMAX(IF(l.property_name = '{$attribute}', u.value_data, null)) AS {$alias}";
                    if ($i + 1 < $count):
                        $query .= ",";
                        $i++;
                    endif;
                endforeach;
            endif;

            //Get subitems count correlated subquery;
            $query .= ",\n(SELECT SUM(total) FROM"
                    . "\n\t(SELECT COUNT(DISTINCT so.object_id) as total,"
                    . "\n\tMAX(IF(sp.property_name = 'media_target', sv.value_data, null)) AS media_target"
                    . "\n\tFROM ?{$this->valueGroup}property_values sv"
                    . "\n\tLEFT JOIN ?properties sp ON sp.property_id = sv.property_id"
                    . "\n\tLEFT JOIN ?objects so ON so.object_id=sv.object_id"
                    . "\n\tWHERE  so.object_type='media'"
                    . "\n\tGROUP BY so.object_id) AS target_counter"
                    . "\nWHERE media_target=o.object_uri) AS media_target_count";

            //The data Joins
            $query .= "\nFROM ?{$this->valueGroup}property_values v"
                    . "\nLEFT JOIN ?properties p ON p.property_id = v.property_id"
                    . "\nLEFT JOIN ?objects o ON o.object_id=v.object_id"
                    //Join the OwnerObjects Properties tables on userid=actorid
                    . "\nLEFT JOIN ?objects q ON q.object_uri=v.value_data AND p.property_name ='media_owner'"
                    . "\nLEFT JOIN ?user_property_values u ON u.object_id=q.object_id"
                    . "\nLEFT JOIN ?properties l ON l.property_id = u.property_id"
            ;

        else:
            $query .="\nFROM ?objects";
        endif;

        $withConditions = false;

        if (!empty($objectId) || !empty($objectURI) || !empty($objectType)):
            $query .="\nWHERE";
            if (!empty($objectType)):
                $query .= "\to.object_type='{$objectType}'";
                $withConditions = TRUE;
            endif;
            if (!empty($objectURI)):
                $query .= ($withConditions) ? "\t AND" : "";
                $query .= "\to.object_uri='{$objectURI}'";
                $withConditions = TRUE;
            endif;
            if (!empty($objectId)):
                $query .= ($withConditions) ? "\t AND \t" : "";
                $query .= "\to.object_id='{$objectId}'";
                $withConditions = TRUE;
            endif;
        endif;

        $query .="\nGROUP BY o.object_id";
        $query .= $this->getListLookUpConditionsClause();
        $query .= $this->getListOrderByStatement();
        $query .= $this->getLimitClause();

        $total = $this->getObjectsListCount($objectType, $properties, $objectURI, $objectId); //Count first
        $results = $this->database->prepare($query)->execute();

        //ALWAYS RESET;
        $this->resetListLookUpConditions();
        $this->setListTotal($total);

        return $results;
    }

    public function addAllMediaTypes(){}
    public function getAllMediaTypesObjectList(){}

    public function getOwner($object, $actorId) {

        if (!is_array($object) || !isset($object['user_name_id']))
            return $object;

        //2.0 THE ACTOR
        $actorObject = new Object();
        $actorName = implode(' ', array($object['user_first_name'], $object['user_last_name']));
        $actorObject::set("objectType", "user"); //@TODO Not only User objects can be actors! You will need to be able to allow other apps to be actors
        $actorObject::set("displayName", $actorName);
        $actorObject::set("id", $actorId);
        $actorObject::set("uri", $object['user_name_id']);

        $actorImage = new MediaLink();
        $actorImageEntity = $this->container->createInstance( Attachment::class )->loadObjectByURI($object['user_photo']);
        $actorImageURL = !empty($object['user_photo']) ? "/file/{$object['user_photo']}/60/60" : "http://placeskull.com/50/50/999999";
        $actorImage::set("type", $actorImageEntity->getPropertyValue("attachment_type"));
        $actorImage::set("url", $actorImageURL);
        $actorImage::set("height", 60);
        $actorImage::set("width", 60);

        if(!empty($object['user_photo'])):
            $actorImage::set("uri", $object['user_photo']);
        endif;
        $actorObject::set("image", $actorImage::getArray());

        $object['media_owner'] = $actorObject::getArray();
        //Remove user model sensitive Data
        foreach (array_keys($this->user->getPropertyModel()) as $private):
            unset($object[$private]);
        endforeach;

        return $object;
    }

    /**
     * Wraps a media entity with accesorry data, like author, attachments, targets, etc...
     * 
     * @param type $object
     * @return type
     */
    public function getObject( Entity $subject ) {

        //1. getActor
        //Media Object;;
        //First get the nature of the media object;
//        if(!is_object($subject)&& !is_a($subject, Entity::class)):
//            $subjectEntity = Platform\Entity::getInstance(); //An empty entity here because it is impossible to guess the properties of this object
//            $subject = $subjectEntity->loadObjectByURI($subject, array()); //Then we load the object
//        endif;

        $object = NULL;
        $mediaObjectURI = $subject->getObjectURI();

        if (!empty($mediaObjectURI)):
            //Create an media object, and fire an event asking callbacks to complete the media object
            $mediaSubject = new Object();
            $mediaObjectType = $subject->getObjectType();

            //Fire the event, passing the mediaSubject by reference
            //Although it looks stupid to need to find out the nature of the media subject before trigger
            //It actually provides an extra exclusion for callback so not all callbacks go to the database
            //so for instance if we found an media subject was a collection, callbacks can first check if the 
            //trigger is to model a collection before diving ing

            //\Library\Event::trigger("onMediaSubjectModel", $mediaSubject, $subject);

            //You never know what callbacks will do to your subject so we just check
            //that the media subject is what we think it is, i.e an media object

            if (is_object($mediaSubject) && method_exists($mediaSubject, "getArray")):
                $object = $mediaSubject::getArray(); //If it is then we can set the media object output vars
            endif;
        else:
            //If there no explicitly defined mediaObjects, in media_object
            //parse media_content for medialinks
            //Parse media targets medialinks
            //@todo;
           // $mediaLinks = Media\MediaLink::parse($data);

        endif;

        return $object;
    }



    /**
     * Adds a new media object to the database
     * @return boolean Returns true on save, or false on failure
     */
    public function store($objectURI = null)
    {
        //@TODO determine the user has permission to post or store this object;
        $this->setPropertyValue("media_owner", $this->user->getPropertyValue("user_name_id"));

        //Determine the target
        if (!$this->saveObject($objectURI, $this->getObjectType())) {
            //There is a problem! the error will be in $this->getError();
            return false;
        }
        return true;
    }

    /**
     * Default display method for every model 
     * @return void;
     */
    public function display() {
        var_dump($this->propertyData); //@TODO Temporary just for testing
    }

}

