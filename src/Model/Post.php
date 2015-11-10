<?php


namespace Budkit\Cms\Model;

use Budkit\Cms\Model\Media\Content;
use Budkit\Datastore\Database;
use Budkit\Helper\Time;

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
class Post extends Content
{

    public function __construct(Database $database, Collection $collection, Container $container, User $user)
    {

        //Constr the parent controller.
        parent::__construct($database, $collection, $container, $user);

        //"label"=>"","datatype"=>"","charsize"=>"" , "default"=>"", "index"=>TRUE, "allowempty"=>FALSE
        //"lets add some more user fields to the post table."
        $this->extendPropertyModel(
            array(
                "media_participants" => array("Post Participants", "mediumint", 10),
                //@TODO, we can use the entity update column for this.
//                "message_updated" => array("Message Updated", "datetime", 200),
//                "message_read" => array("Message Read", "mediumtext", 600),
            ), "media"
        );
    }


    public function getMessages($active = NULL)
    {

        $_users = $this->load->model("user", "member");
        $_me = $this->user->get("user_name_id");
        $_messages = $this->setListLookUpConditions("message_participants", "(^|,){$_me}(,|$)", "AND", FALSE, TRUE, "RLIKE")
            ->getObjectsList("message");
        $rows = $_messages->fetchAll();
        $messages = array("totalItems" => 0);
        //Loop through fetched attachments;
        //@TODO might be a better way of doing this, but just trying
        foreach ($rows as $row) {

            $_member = $_users->loadObjectByURI($row['message_author']);
            //Has this user read this message?
            $readby = explode(",", $row['message_read']);

            if (!in_array($this->user->get("user_name_id"), $readby) && $row['message_author'] <> $this->user->get("user_name_id")):
                $row['message_status'] = 'unread';
            endif;

            if ($active == $row['object_uri']):
                $row['message_status'] = 'open';
            endif;

            $row['message_body'] = strip_tags(html_entity_decode(trim($row['message_body'])));
            $row['message_author'] = $_member->getPropertyData();
            $row['message_author']['user_full_name'] = $_users->getFullName($_member->getPropertyValue('user_first_name'), NULL, $_member->getPropertyValue("user_last_name"));

            $messages["items"][] = $row;
            $messages["totalItems"]++;
        }

        return $messages;
    }

}

