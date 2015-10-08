<?php

namespace Budkit\Cms\Model\Media;

/**
 * Media Link Model Class
 *
 * Some types of objects may have an alternative visual representation in the 
 * form of an image, video or embedded HTML fragments. A Media Link represents a 
 * hyperlink to such resources.
 *
 * @category  Application
 * @package   Data Model
 * @license   http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version   1.0.0
 * @since     Jan 14, 2012 4:54:37 PM
 * @author    Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 * 
 */
final class MediaLink {

    /**
     * ==========================
     * Global Medialink variables
     * ========================== */
    /*
     * Identifies the object type 
     * 
     * @var string (required)
     */
    public static $objectType = "medialink";

    /**
     * The resource mime/type. Valid values, along with type-specific parameters include
     * 
     * @var string (optional)
     */
    public static $type = "";
    
    
    /**
     * Defines the medialink file name. Include the extension
     * 
     * @var type 
     */
    public static $name = "";

    /**
     * A text title, describing the resource.
     * 
     * @var string (optional)
     */
    public static $title = "";

    /**
     * The name of the author/owner of the resource.
     * 
     * @var string (optional)
     */
    public static $authorName = "";

    /**
     * A URL for the author/owner of the resource.
     * 
     * @var string (optional)
     */
    public static $authorUrl = "";

    /**
     * The suggested cache lifetime for this resource, in seconds. Consumers may choose to use this value or not.
     * 
     * @var string (optional)
     */
    public static $cacheAge = "";

    /**
     * The url of the resource provider.
     * 
     * @var string (optional)
     */
    public static $providerUrl = "";

    /**
     * The name of the resource provider.
     * 
     * @var string (optional) 
     */
    public static $providerName = "";

    /**
     * A URL to a thumbnail image representing the resource. The thumbnail must 
     * respect any maxwidth and maxheight parameters. If this paramater is present, 
     * thumbnailWidth and thumbnailHeight must also be present.
     * 
     * @var string (optional)
     */
    public static $thumbnailUrl = "";

    /**
     * The width of the optional thumbnail. If this paramater is present, thumbnailUrl 
     * and thumbnailHeight must also be present.
     * 
     * @var string (optional) 
     */
    public static $thumbnailWidth = "";

    /**
     * The height of the optional thumbnail. If this paramater is present, 
     * thumbnailUrl and thumbnailWidth must also be present
     * 
     * @var string (optional) 
     */
    public static $thumbnailHeight = "";


    /**
     * =================================
     * Type Specific Medialink parameters
     * ================================= */

    /**
     * A hint to the consumer about the length, in seconds of the media resource identified by the url property. 
     * A media link may contain a duration property when the target resource is a time-based media item such as an audio or video
     * 
     * @var interger (required for video type)
     */
    public static $duration = 0;

    /**
     * A hint to the consumer about the height, in pixels of the media resource identified by the url property. 
     * A media link may contain a height property when the targe resource is a visual mediat item such as an image, video or embeddable HTML page.
     * 
     * @var interger (required for rich, photo, and video types)
     */
    public static $height = 0;

    /**
     *
     * @var string (required for rich and some video types) 
     */
    public static $html = "";

    /**
     * A media link MUST have a URL property.
     * 
     * @var string 
     */
    public static $url = "";

    /**
     * An IRI identifying a resource providing an HTML representation of the object. 
     * @var string 
     */
    public static $uri;

    /**
     * A hint to the consumer about the width, in pixels of the media resource identified by the url peroperty. 
     * A media link may contain a width property when the target resource is a visual media item such as an image, video or embeddable HTML page
     * @var interger 
     */
    public static $width = 0;

    /**
     * Creates a new Medialink with defaultvariables
     * 
     * @return \Application\System\Models\Media\MediaLink
     */
    public static function getNew() {

        //Get the default properties;
        $class = new MediaLink;
        $medialink = get_class_vars(get_class($class));

        //Reset this class!
        foreach ($medialink as $name => $default):
            $class::set($name, null);
            $class::set("objectType", "medialink");
        endforeach;

        return $class;
    }

    /**
     * Returns an array with object properties names as keys. 
     * Empty property values are omitted
     * 
     * @return type
     */
    public static function getArray() {

        $object = new \ReflectionClass(self);
        $properties = $object->getProperties(\ReflectionProperty::IS_PUBLIC);
        $array = array();

        foreach ($properties as $property) {
            $value = $property->getValue();
            if (!empty($value)) {
                $array[$property->getName()] = $value;
            }
        }
        return $array;
    }

    /**
     * Parses a string returns media links etc
     * 
     * @param type $string
     */
    public static function parse(&$string) {

        $media = array(
            "objects" => array(), //All @mentions, you can mention anytype of object
            "hashes" => array(), //You can use any kind of hashes
            "links" => array(), //Will attempt to fetch link descriptions where possible
        );

        //Match mentions, urls, and hastags
        preg_match_all('#@([\\d\\w]+)#', $string, $mentions);
        preg_match_all('/#([\\d\\w]+)/', $string, $hashTags);
        preg_match_all('/((http|https|ftp|ftps)\:\/\/)([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?([a-zA-Z0-9\-\.]+)\.([a-zA-Z]{2,3})(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?/', $data, $openLinks);

        //print_R($mentions);
        //print_R($hashTags);
        //print_R($openLinks);
        //$string = "parsed";

        return $media;
    }

    /**
     * Sets an object class property
     * 
     * @param type $property
     * @param type $value
     */
    public static function set($property, $value = NULL) {

        $object = new \ReflectionClass(self);
        $object->setStaticPropertyValue($property, $value);

        return true;
    }

    /**
     * Gets an object class property
     * 
     * @param type $property
     * @param type $default
     */
    public static function get($property, $default = NULL) {

        $object = new \ReflectionClass(self);
        $value = $object->getStaticPropertyValue($property);

        //If there is no value return the default
        return (empty($value)) ? $default : $value;
    }
}