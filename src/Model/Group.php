<?php


namespace Budkit\Cms\Model;
use Budkit\Datastore\Model\DataModel;

/**
 * Privacy group model
 * 
 * @category  Application
 * @package   Data Model
 * @license   http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version   1.0.0
 * @since     Jan 14, 2012 4:54:37 PM
 * @author    Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 * 
 */
class Groups extends DataModel{

    /**
     * Default model method
     * @return void;
     */
    public function display() {
        return false;
    }

    /**
     * Stores Authority Data to the database
     * @param array $data
     * @return boolean true on success 
     */
    public function store() {

        //2. Saniitize the data
        $groupTitle = $this->input->getString("group-title");
        $groupParent = $this->input->getInt("group-parent");
        $groupId = $this->input->getInt("group-id");
        $groupDescription = $this->input->getString("group-description");

        $groupName = strtoupper(str_replace(array(" ", "(", ")", "-", "&", "%", ",", "#"), "", $groupTitle));

        $gData = array(
            "group_id" => $groupId,
            "group_name" => $groupName,
            "group_title" => $groupTitle,
            "group_parent_id" => empty($groupParent) ? 0 : (int) $groupParent,
            "group_description" => $groupDescription,
            "group_owner" => $this->get("user")->get("user_name_id"),
            "group_lft" => 1,
            "group_rgt" => 2
        );

        //3. Load and prepare the Authority Table
        $table = $this->load->table("?groups");

        if (!$table->bindData($gData)) {
            //print_R($table->getErrors());
            throw new \Platform\Exception($table->getError());
            return false;
        }

        //4. Are we adding a new row
        if ($table->isNewRow()) {

            if (empty($groupName) || empty($groupTitle)) {
                $this->setError(_t('Every new Group must have a defined Title'));
                return false;
            }

            $rgt = $parent = $this->database->select("MAX(group_rgt) AS max")->from("?groups")->where("group_owner", $this->database->quote($table->getRowFieldValue('group_owner')))->prepare()->execute()->fetchObject();

            if (!empty($groupParent)):
                //Get the parent left and right value, to make space
                $parent = $this->database->select("group_lft, group_rgt")->from("?groups")->where("group_id", (int) $table->getRowFieldValue('group_parent_id'))->prepare()->execute()->fetchObject();
                //echo $parent->rgt;

                $this->database->update("?groups", array("group_lft" => "group_lft+2"), array("group_lft >" => ($parent->group_rgt - 1 ), "group_owner" => $this->database->quote($table->getRowFieldValue('group_owner'))));
                $this->database->update("?groups", array("group_rgt" => "group_rgt+2"), array("group_rgt >" => ($parent->group_rgt - 1), "group_owner" => $this->database->quote($table->getRowFieldValue('group_owner'))));

                $table->setRowFieldValue("group_lft", $parent->group_rgt);
                $table->setRowFieldValue("group_rgt", $parent->group_rgt + 1);
            else:
                $table->setRowFieldValue("group_lft", $rgt->max + 1);
                $table->setRowFieldValue("group_rgt", $rgt->max + 2);
            endif;
        }

        //5. Save the table modifications
        if (!$table->save()) {
            return false;
        }

        return true;
    }

    /**
     * Loads a row of authority groups
     * @todo Implement authority model load
     * @return void
     */
    public function load() {
        
    }

    /**
     * Deletes an authority group from the datastore
     * @todo Implement the authority model delete
     * @return void
     */
    public function delete() {
        
    }

    /*
     * Validates new authority input
     * @todo Validate new authority input
     * @return void
     */

    public function validate() {
        
    }

    /**
     * Returns a processed array of authority groups
     * @return array
     */
    public function getGroups() {
        //The current platform user
        $user = $this->get("user");

        //The get group query;
        $statement = $this->database->select("g.*, count(m.object_group_id) AS members_count")
                        ->from("?groups g")
                        ->join("?objects_group m", "g.group_id=m.group_id", "LEFT")
                        ->where("g.group_owner", $this->database->quote($user->get("user_name_id")))
                        ->groupBy("g.group_name")
                        ->orderBy("g.group_lft", "ASC")->prepare();

        $results = $statement->execute();

        //Authorities Obbject
        $rows = $results->fetchAll();
        $groups = array();
        $right = array();
        foreach ($rows as $group) {

            //$lastrgt = end($right);

            if (count($right) > 0) {
                while(count($right) > 0 && $group['group_rgt'] > end($right)) {
                    array_pop($right);
                }
            }

            //Authority Indent
            $group["indent"] = sizeof($right);
            //Authority Permissions;
            $groups[] = $group;
            $right[] = $group['group_rgt'];
        }

        return $groups;
    }

    /**
     * Returns an instance of the authority class
     * @staticvar object $instance
     * @return object Authority
     */
    public static function getInstance() {
        static $instance;
        //If the class was already instantiated, just return it
        if (isset($instance))
            return $instance;
        $instance = new self;
        return $instance;
    }

}

