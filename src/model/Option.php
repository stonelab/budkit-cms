<?php


namespace Budkit\Cms\Model;
use Budkit\Datastore\Model\DataModel;

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
class Options extends DataModel {

    /**
     * Default display method for every model 
     * @return boolean false
     */
    public function display() {
        return false;
    }

    /**
     * Saves options to the database, inserting if none exists or updating on duplicate key
     * 
     * @param array $options An array of options
     * @param string $group A unique string representing the options group
     * @return boolean
     */
    public function save($options, $group = null) {

        if (!is_array($options) || empty($options)) {
            $this->setError("No options passed to be saved");
            return false;
        }
        //Inser the data if not exists, or update if it does exists;
        $table = $this->load->table("?options");
        $shortgun = "REPLACE INTO ?options (`option_group_id`,`option_name`,`option_value`)\t";

        //$this->database->startTransaction();
        $values = array();
        
        foreach ($options as $group => $option):
            if (is_array($option)):
                foreach ($option as $item => $value):
                    $binder = "( " . $this->database->quote($group) . "," . $this->database->quote($item) . "," . $this->database->quote($value) . ")";
                    $values[] = $binder;
                endforeach;
            else:
                $item = $group;
                $value = $option;
                $binder = "( " . $this->database->quote("general") . "," . $this->database->quote($item) . "," . $this->database->quote($value) . ")";
                $values[] = $binder;
            endif;        
        //$table->insert($binder, T);
        endforeach;
        
        $primaryKey = $table->keys();
        $shortgunval = implode(',', $values);
        $shortgun .= "VALUES\t" . $shortgunval;
        //$shortgun   .= "\tON DUPLICATE KEY UPDATE ".$primaryKey->Field."=VALUES(`option_group_id`)+VALUES(`option_name`)+VALUES(`option_value`)";
        //echo $shortgun; die;
        //Run the query directly
        if (!$this->database->exec($shortgun)) {
            $this->setError($this->database->getError());
            return false;
        }
        return true;
    }
}

