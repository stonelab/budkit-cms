<?php


namespace Budkit\Cms\Model;
use Budkit\Datastore\Database;
use Budkit\Datastore\Model\Entity;
use Budkit\Dependency\Container;

/**
 * Options management model
 *
 * Manages system options
 *
 * @category  Application
 * @package   Data Model
 * @license   http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version   1.0.0
 * @since     Jan 14, 2012 4:54:37 PM
 * @author    Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 *
 */
class Collection extends Entity {

    public function __construct(Database $database, Container $application) {

        parent::__construct($database, $application);

        //"label"=>"","datatype"=>"","charsize"=>"" , "default"=>"", "index"=>TRUE, "allowempty"=>FALSE
        $this->definePropertyModel(
            array(
                "collection_title" => array("Collection Title", "mediumtext", 100),
                "collection_items" => array("Collection Items", "longtext", 2000),
                "collection_thumbnail" => array("Collection Thumbnail", "mediumtext", 200),
                "collection_size" => array("Collection Size", "smallint", 10),
                "collection_description" => array("Collection Description", "mediumtext", 200),
                "collection_tags" => array("Collection Tags", "mediumtext", 100),
                "collection_owner" => array("Collection Owner", "mediumtext", 100)
            ), "collection"
        );
        //$this->definePropertyModel( $dataModel ); use this to set a new data models or use extendPropertyModel to extend existing models
        //$this->defineValueGroup("attachment"); //Tell the system we are using a proxy table
    }

    /**
     * Default display method for every model
     * @return boolean false
     */
    public function display() {
        return false;
    }

    /**
     * Models a collection media object for media feeds
     *
     * @param type $mediaObject
     * @param type $mediaObjectType
     * @param type $mediaObjectId
     *
     * return void;
     */
    public static function mediaObject(&$mediaObject, $collection) {

        //If the media object is not a collection! skip it

//1.Load the collection!
        if(!is_object($collection)&&is_a($collection, Entity::class)):
            $thisModel = new Self;
            $attachment = $thisModel->loadObjectByURI($collection);
        endif;
//If the media object is not a collection! skip it
        $objectTypeshaystack = array("collection");

        if (!in_array($collection->getObjectType(), $objectTypeshaystack))
            return; //Nothing to do here if we can't deal with it!

        $collectionObject = new Media\Collection;
        //2.Get all the elements in the collection, limit 5 if more than 5
        //3.Trigger their timeline display
        $collectionObject->set("objectType", "collection");
        $collectionObject->set("uri", $collection->getObjectURI());

        //Now lets populate our collection with Items
        $collectionItems = $collection->getPropertyValue("collection_items");
        $collectionItemize = explode(",", $collectionItems);
        $collectionObject->set("totalItems", count($collectionItemize));

        if (is_array($collectionItemize) && !empty($collectionItemize)) {
            $items = array();
            foreach ($collectionItemize as $item) {
                $itemObject = Media\MediaLink::getNew();
                //@TODO Will probably need to query for objectType of items in collection?
                //@TODO Also this will help in removing objects from collections that have previously been deleted
                $itemObjectEntity = $thisModel->load->model("attachment", "system")->loadObjectByURI( $item ); //Load the item with the attachment to get all its properties
                //Now check object_id exists;
                //If not delete the object all together;
                //Also check if attachments_src is defined and exsits;
                //If attachments id does not exists, delete the item from this collection;

                $itemObjectURL = !empty($item) ? "/system/object/{$item}/" : "http://placeskull.com/100/100/999999";
                $itemObject->set("url", $itemObjectURL);
                $itemObject->set("uri", $item);
                $itemObject->set("height", null);
                $itemObject->set("width", null);
                $itemObject->set("type", $itemObjectEntity->getPropertyValue("attachment_type") );
                $itemObject->set("name", $itemObjectEntity->getPropertyValue("attachment_name"));
                $items[] = $itemObject::getArray();

                unset($itemObject);
            }
            $collectionObject->set("items", $items);
        }
        //Now set the collection Object as the media Object
        $mediaObject = $collectionObject;

        unset($collection);
        unset($collectionObject);

        //All done
        return true;
    }
}

