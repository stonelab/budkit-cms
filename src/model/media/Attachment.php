<?php

namespace Budkit\Cms\Model\Media;

use Budkit\Datastore\Database;
use Budkit\Datastore\Model\Entity;
use Budkit\Dependency\Container;

/**
 * Attachment management model
 *
 * All attachments are saved as objects in EAV database
 *
 * @category  Application
 * @package   Data Model
 * @license   http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version   1.0.0
 * @since     Jan 14, 2012 4:54:37 PM
 * @author    Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 *
 */
class Attachment extends Content {

    /**
     * The ultimate max file size in bytes
     * Do not change this (set at 25MB)
     */
    private $_postMaxSize = 26214400;

    /**
     * Characters allowed in the file name
     * (in a Regular Expression format)
     */
    private $_validChars = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';

    /**
     * The upload file type
     */
    private $_fileType = null;

    /**
     * The Last uploaded File
     */
    private $_lastUploadedItem = null;

    /**
     * Max File Length
     */
    private $_maxNameLength = 100;

    /**
     * The current user uploading the attachment
     * Nothing fancy, just so we know where to
     * save the file
     * @var type
     */
    public $_owner = null;

    /**
     * The default file types that can be added using the attachment models
     * use $this->allowedTypes(array()) before $this->save() to define which file types
     * to accept.
     *
     * @static array
     */
    public $allowed = array();

    /**
     *
     * @var Collection
     */
    protected $collection;

    /**
     * Defines the attachment properties
     *
     * @return void
     */
    public function __construct(Database $database, Collection $collection, Container $container) {

        parent::__construct($database);

        $this->collection = $collection;
        $this->input = $container->input;

        //"label"=>"","datatype"=>"","charsize"=>"" , "default"=>"", "index"=>TRUE, "allowempty"=>FALSE
        $this->extendPropertyModel(
            array(
                "attachment_name" => array("Attachment Name", "mediumtext", 50),
                "attachment_title" => array("Attachment Title", "mediumtext", 100),
                "attachment_size" => array("Attachment Size (bytes)", "mediumint", 50),
                "attachment_src" => array("Attachment Source", "mediumtext", 100),
                "attachment_ext" => array("Attachment Extension", "mediumtext", 10),
                "attachment_owner" => array("Attachment Owner user_name_id", "mediumtext", 100),
                "attachment_type" => array("Attachment Content Type", "mediumtext", 100)
            ), "attachment"
        );
        //$this->definePropertyModel( $dataModel ); use this to set a new data models or use extendPropertyModel to extend existing models
        $this->defineValueGroup("attachment"); //Tell the system we are using a proxy table
    }

    /**
     * Default display method for every model
     * @return boolean false
     */
    public function display() {
        return false;
    }

    /**
     * Searches the database for attachments
     *
     * @param type $query
     * @param type $results
     */
    public static function search($query, &$results = array()) {

        $attachments = static::getInstance();

        if (!empty($query)):
            $words = explode(' ', $query);
            foreach ($words as $word) {
                $_results =
                    $attachments->setListLookUpConditions("attachment_name", $word, 'OR')
                        ->setListLookUpConditions("attachment_title", $word, 'OR')
                        ->setListLookUpConditions("attachment_description", $word, 'OR')
                        ->setListLookUpConditions("attachment_tags", $word, 'OR');
            }

            $_results = $attachments
                ->setListLookUpConditions("attachment_owner", array($attachments->user->get("user_name_id")),"AND",true)
                ->setListOrderBy("o.object_created_on", "DESC")
                ->getObjectsList("attachment");
            $rows = $_results->fetchAll();

            $browsable = array("image/jpg", "image/jpeg", "image/png", "image/gif");
            //Include the members section
            $documents = array(
                "filterid" => "attachments",
                "title" => "Documents",
                "results" => array()
            );
            //Loop through fetched attachments;
            //@TODO might be a better way of doing this, but just trying
            foreach ($rows as $attachment) {
                $document = array(
                    "title" => $attachment['attachment_title'], //required
                    "description" => "", //required
                    "type" => $attachment['object_type'],
                    "object_uri" => $attachment['object_uri']
                );
                if (in_array($attachment['attachment_type'], $browsable)):
                    $document['icon'] = "/system/object/{$attachment['object_uri']}/resize/170/170";
                    $document['link'] = "/system/media/photo/view/{$attachment['object_uri']}";
                else:
                    $document['media_uri'] = $attachment['object_uri'];
                    $document['link'] = "/system/object/{$attachment['object_uri']}";
                endif;

                $documents["results"][] = $document;
            }
            //Add the members section to the result array, only if they have items;
            if (!empty($documents["results"]))
                $results[] = $documents;

        endif;

        return true;
    }

    /**
     * Defines allowed attachment types before save
     * Any not explicitly defined here will be skipped
     *
     * @deprecated since version 1.0.0 use attachments.ini
     * @param array $types
     */
    public function setAllowedTypes(array $types) {
        if (is_array($types)) {
            $this->allowed = $types;
        }
    }

    /**
     * Sets the Owners name Id such that attachments are
     * saved into subfolders
     * 8
     * @param string $name
     */
    public function setOwnerNameId($name) {
        $this->_owner = $name;
    }

    /**
     * Saves options to the database, inserting if none exists or updating on duplicate key
     *
     * @param array $options An array of options
     * @param string $group A unique string representing the options group
     * @return boolean true. Will throw an exception upon any failure.
     */
    public function store($file) {

        $fileHandler = \Library\Folder\Files::getInstance();
        $uploadsFolder = $this->config->getParam('site-users-folder', '/users');
        $allowedTypes = $this->allowed;
        if (empty($allowedTypes)):
            $attachmentTypes = $this->config->getParamSection("attachments");
            foreach ($attachmentTypes as $group => $types):
                $allowedTypes = array_merge($allowedTypes, $types);
            endforeach;
        endif;
        //Check User Upload Limit;
        //Check File upload limit;
        //Validate the file

        $fileName = preg_replace('/[^' . $this->_validChars . ']|\.+$/i', "", basename($file['name']));
        if (strlen($fileName) == 0 || strlen($fileName) > $this->_maxNameLength) {
            $this->setError(_("Invalid file name"));
            throw new \Platform\Exception($this->getError());
        }
        //Check that the file has a valid extension
        $fileExtension = $fileHandler->getExtension($fileName);

        if (!array_key_exists(strtolower($fileExtension), $allowedTypes)) {
            $this->setError(_("Attempting to upload an invalid file type"));
            throw new \Platform\Exception($this->getError());
        }
        //The target folder
        //Check that folder exists, otherwise create it and set the appropriate permission;
        $uploadsFolder = FSPATH . $uploadsFolder;
        if (isset($this->_owner)) {
            $uploadsFolder .= DS . $this->_owner;
        }
        $uploadsFolder .= DS . "attachments"; //All uploads are saved in the attachments folder
        $uploadsFolder = str_replace(array('/', '\\'), DS, $uploadsFolder);

        if (!$fileHandler->is($uploadsFolder, true)) { //if its not a folder
            $folderHandler = \Library\Folder::getInstance();
            if (!$folderHandler->create($uploadsFolder)) {
                $this->setError(_("Could not create the target uploads folder. Please check that you have write permissions"));
                throw new \Platform\Exception($this->getError());
            }
        }

        $_uploadFileName = str_replace(array(" "), "_", $fileName);
        $uploadFileName = $uploadsFolder . DS . time().$_uploadFileName; //adding a timestamp to avoid name collisions
        if (!move_uploaded_file($file['tmp_name'], $uploadFileName)) {
            $this->setError(_("Could not move the uploaded folder to the target directory"));
            throw new \Platform\Exception($this->getError());
        }

        //Get the uploaded file extension type.
        $this->_fileType = $fileHandler::getMimeType($uploadFileName);
        //Validate the file MimeType against the allowed extenion type, if fails,
        //delete the file and throw an error.

        foreach (
            array(
                "media_title" => basename($file['name']),
                "media_actor"=> $this->user->get("user_id"),
                "attachment_name" => $fileName,
                "attachment_title" => basename($file['name']), //@todo Wil need to check $file[title],
                "attachment_size" => $file['size'],
                "attachment_src" => str_replace(FSPATH, '', $uploadFileName),
                "attachment_ext" => $fileExtension,
                "attachment_owner" => $this->user->get("user_name_id"),
                "attachment_type" => $this->_fileType
            ) as $property => $value):
            $this->setPropertyValue($property, $value);
        endforeach;

        if (!$this->saveObject(NULL, "attachment")) { //Null because the system can autogenerate an ID for this attachment
            $fileHandler->delete($uploadFileName);
            $this->setError(_("Could not store the attachment properties to the database"));
            throw new \Platform\Exception($this->getError());
        }

        return true;
    }

    /**
     * Prepares and executes a database query for fetching media objects
     * @param interger $objectId
     * @param string $objectURI
     * @return object Database resultset
     */
    final private function getAttachmentObjectsList($objectType = 'attachment', $objectURI = NULL, $objectId = NULL) {
        //Join Query
        //$objectType = 'media';
        $query = "SELECT o.object_id, o.object_uri, o.object_type,o.object_created_on, o.object_updated_on, o.object_status";
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
            $_actorProperties = $this->load->model("profile", "member")->getPropertyModel();
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
            //The data Joins
            $query .= "\nFROM ?attachment_property_values v"
                . "\nLEFT JOIN ?properties p ON p.property_id = v.property_id"
                . "\nLEFT JOIN ?objects o ON o.object_id=v.object_id"
                //Join the UserObjects Properties tables on userid=actorid
                . "\nLEFT JOIN ?objects q ON q.object_uri=v.value_data AND p.property_name ='attachment_owner'"
                . "\nLEFT JOIN ?user_property_values u ON u.object_id=q.object_id"
                . "\nLEFT JOIN ?properties l ON l.property_id = u.property_id"
            ;

        else:
            $query .="\nFROM ?objetcs";
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
        $query .= $this->setListOrderBy(array("o.object_updated_on"), "DESC")->getListOrderByStatement();

        $result = $this->database->prepare($query)->execute();

        return $result;
    }

    final public static function removeMedia(&$objectID, &$objectURI=NULL, &$objectType="NULL"){

        if(empty($objectType)||empty($objectID)||$objectType!=="attachment") return; //Proceed if attachment;

        $attachments = static::getInstance();
        $attachment  = $attachments->loadObjectById($objectID);

        $file        = $attachment->getPropertyValue("attachment_src");
        $fileHandler = \Library\Folder\Files::getInstance();

        $directory   = $fileHandler->getPath($file);
        $name        = $fileHandler->getName($file);
        $extension   = $fileHandler->getExtension($file);

        $pattern    = FSPATH.$directory.DS.$name.'*.'.$extension;
        $files      = glob($pattern);

        foreach ($files as $filename) {
            $fileHandler->delete($filename);
        }

    }

    /**
     * Returns an attachment object, wrapped in a media/Object class for
     * presentation. Suitable for viewing single file attachments;
     * All this method does, is load the attachment, then manuall adds
     * attachment properties to a media object;
     *
     * @return collection;
     */
    final public function getMedia($objectType = "attachment", $objectURI = NULL, $objectId = NULL) {
       return Parent::getMedia($objectType, $objectURI, $objectId);
    }


    final public function getAttachmentsFromInput(){

        $mediaObject = null;

        $attachments = $this->input->getArray("attachment", "" , "files");

        if (is_array($attachments) && !empty($attachments)) {
            if (sizeof($attachments) > 1) {
                //Create a collection and link to the object iD
                $collection = $this->collection;
                $collection->setPropertyValue("collection_items", implode(',', $attachments));
                $collection->setPropertyValue("collection_size", count($attachments));
                $collection->setPropertyValue("collection_owner", $this->user->get("user_name_id"));
                //Should we add the media body to the collection description? there is really no need to,
                //As every item will need to be described in details later
                if (!$collection->saveObject(null, "collection")) {
                    $this->setError("Could not save attached objects");
                    $mediaObject = NULL;
                }
                //If however we could save, then get the last saved object ID;
                $mediaObject = $collection->getLastSavedObjectURI();
                unset($collection); //destroys the collection object?
            } else {
                $oneobject = reset($attachments); //Validate. String only
                $mediaObject = !$this->validate->alphaNumeric($oneobject) ? null : $oneobject; //Maybe a much harder validation
            }
            $this->setPropertyValue("media_object", $mediaObject);
        }

    }



    /**
     * Placeholder images
     *
     * @param type $fileId
     * @param type $filePath
     * @param type $contentType
     * @param type $params
     */
    final public static function place($fileId="", $filePath="", $contentType="image/png", $params=array()){

        $attachments    = static::getInstance();
        $fullPath       = empty($filePath) ? FSPATH.$attachments->config->getParam("placeholder", "" , "content") : $filePath;

        $browsable = array("image/jpg", "image/jpeg", "image/png", "image/gif");

        //Commands
        if (is_array($params)):
            $modifiers  = $params;
            $modifier   = array_shift($modifiers);
            $allowed    = array("resize"); //a list of allowed modifies
            if (in_array($modifier, $allowed) && method_exists($attachments, $modifier)) { //make 
                $fullPath = $attachments::$modifier($fullPath, $modifiers);
                $fd = fopen($fullPath, "rb");
            }
        endif;

        //Attempt to determine the files mimetype
        $ftype = !empty($contentType) ? $contentType : \Library\Folder\Files::getMimeType($fullPath);

        //Get the file stream
        if (!$fd) {
            $fd = fopen($fullPath, "rb");
        }

        if ($fd) {
            $fsize = filesize($fullPath);
            $fname = basename($fullPath);
            $headers = array(
                "Pragma" => null,
                "Cache-Control" => "",
                "Content-Type" => $ftype,
            );
            foreach ($headers as $name => $value) {
                $attachments->output->unsetHeader($name);
                $attachments->output->setHeader($name, $value);
            }
            if (in_array($ftype, $browsable)):
                fpassthru($fd);
                fclose($fd);
                $attachments->output->setFormat('raw', array()); //Headers must be set before output 
                $attachments->output->display();
            else: //If the file is not browsable, force the browser to download the original file;
                //Move the file to the temp public download directory
                $downloadPath = FSPATH . "public" . DS . "downloads" . DS . $fileId;
                //For personalized link we will need to randomize the filename.
                $downloadPath.= Platform\Framework::getRandomString(5); //So people won't be guessing!;;
                $downloadPath.= "." . \Library\Folder\Files::getExtension($fname);
                if (\Library\Folder\Files::copy($fullPath, $downloadPath)) {
                    if (file_exists($downloadPath)):

                        //We still want to delete the file even after the user
                        //is gone
                        ignore_user_abort(true);
                        //$attachment->output->setHeader("Expires", "0");
                        //Content-Disposition is not part of HTTP/1.1
                        $downloadName = basename($downloadPath);
                        $attachments->output->setHeader("Content-Disposition", "inline; filename={$downloadName}");
                        //Will need to restart the outputbuffer with no gziphandler
                        $noGzip = $attachments->output->restartBuffer(null); //use null rather than "" for no gzip handler;
                        ob_end_clean(); //ob level 0; output buffering and binary transfer is a nightmare

                        $attachments->output->setHeader("Cache-Control", "must-revalidate");
                        $attachments->output->setHeader("Content-Length", $fsize);
                        readfile($downloadPath);

                        //Delete after download.
                        unlink($downloadPath);
                        //$attachment->output->abort();
                        $attachments->output->setFormat('raw', array()); //Headers must be set before output 
                        $attachments->output->display();
                    endif;
                }
                fclose($fd);
                $attachments->output->setFormat('raw', array()); //Headers must be set before output 
                $attachments->output->display();
            endif;
            //$attachment->output->setHeader("Content-Disposition", "attachment; filename=\"" . $fname . "\"");
            //$attachment->output->setHeader("Content-length", $fsize);
        }

        //Here is the attachment source, relative to the FSPATH;
        //print_r($attachment->getPropertyValue("attachment_src"));
    }

    /**
     * Displays an attachment
     *
     * @param type $object
     * @param type $params
     */
    final public static function load(&$object, &$params) {
        //Relaod the object
        $attachments = static::getInstance();
        $attachment = & $object;
        //if is object $object
        if (!is_a($attachment, Entity::class)) {
            //Attempt to determine what type of object this is or throw an error
            $attachment = $attachments->loadObjectByURI($attachment);
            //Make sure its an object;
        }

        if ($attachment->getObjectType() !== "attachment")
            return false; //we only deal with attachments, let others deal withit

        $fileId   = $attachment->getObjectType();
        $filePath = FSPATH . DS . $attachment->getPropertyValue("attachment_src");
        $contentType = $attachment->getPropertyValue("attachment_type");

        static::place($fileId, $filePath, $contentType, $params);
    }

    /**
     * Resizes an image
     *
     * @param type $file
     * @param type $params
     */
    final public static function resize($file, $params) {
        //die;
        $fileHandler = \Library\Folder\Files::getInstance('image');
        $resizable = array("jpg", "gif", "png", "jpeg");

        //If there is no file
        if (empty($file))
            return $file;
        $fileExtension = $fileHandler->getExtension($file);

        //If we can't resize this type of file
        if (!in_array(strtolower($fileExtension), $resizable))
            return $file; //If we can't resize it just return the file





        //We need at least the width or height to resize;
        if (empty($params))
            return false;
        $width = isset($params[0]) ? $params[0] : null;
        $height = isset($params[1]) ? $params[1] : null;

        $isSquare = ($width == $height) ? true : false;
        //NewName = OriginalName-widthxheight.OriginalExtension
        $fileName = $fileHandler->getName($file);
        $filePath = $fileHandler->getPath($file);

        $target = $filePath . DS . $fileName . (isset($width) ? "-" . $width : null) . (isset($height) ? "x" . $height : null) . "." . $fileExtension;

        if (!$fileHandler->resizeImage($file, $target, $width, $height, $isSquare)) {
            return false; //There was a problem and we could not resize the file
        }
        return $file = $target;
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
    public static function mediaObject(&$mediaObject, $attachment) {

        //Allowed media objects
        $types = \Library\Config::getParam("allowed-types", array(), "attachments");

        //1.Load the collection!
        if(!is_object($attachment)&&is_a($attachment,'Platform\Entity')):
            $thisModel = new Self;
            $attachment = $thisModel->loadObjectByURI($attachment);
        endif;

        //If the media object is not a collection! skip it
        $objectTypeshaystack = array("attachment");

        if (!in_array($attachment->getObjectType(), $objectTypeshaystack))
            return; //Nothing to do here if we can't deal with it!

        $attachmentObject = new MediaLink();
        //2.Get all the elements in the collection, limit 5 if more than 5
        //3.Trigger their timeline display
        $mediaObjectURI = $attachment->getObjectURI();

        $attachmentObject::set("objectType", "attachment");
        $attachmentObject::set("uri", $attachment->getObjectURI());

        //Now lets populate our collection with Items
        //@TODO Will probably need to query for objectType of items in collection?
        //@TODO Also this will help in removing objects from collections that have previously been deleted
        $attachmentObjectURL = !empty($mediaObjectURI) ? "/system/object/{$mediaObjectURI}" : "http://placeskull.com/100/100/999999";
        $attachmentObject->set("url", $attachmentObjectURL);
        $attachmentObject->set("uri", $mediaObjectURI);

        //AttachmentTypes
        //$mediaType  =  $attachment->getPropertyValue("attachment_type");

        $attachmentObject->set("name", $attachment->getPropertyValue("attachment_name"));
        $attachmentObject->set("type", $attachment->getPropertyValue("attachment_type"));
        $attachmentObject->set("height", null);
        $attachmentObject->set("width", null);

        //echo $mediaObjectURI;
        //Now set the collection Object as the media Object
        $mediaObject = $attachmentObject;

        return true;
    }
}

