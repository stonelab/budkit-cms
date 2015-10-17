<?php

namespace Budkit\Cms\Controller\Admin\Settings;
use Budkit\Cms\Controller\Admin\Settings;

/**
 * Admin settings action controller
 *
 * Displays and update system configuration settings. 
 *
 * @category  Application
 * @package   Action Controller
 * @license   http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version   1.0.0
 * @since     Jan 14, 2012 4:54:37 PM
 * @author    Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 * @todo      System manage action methods
 */
class Maintenance extends Settings {


    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";

        // echo "Pages admin";
        $this->view->setData("title", t("Settings » Maintenance"));

        $this->view->addToBlock("main", "import://admin/settings/maintenance");
        //$this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/dashboard");

    }

    public function check() {
        $update = '<a href="/settings/system/maintenance/update">Please update now</a>';
        //Check
        $this->alert($update, "Version 0.9.2 is available.", "info");
        //
        return $this->index();
    }

    public function update() {
        //Check
        $this->alert("BudKit 0.9.2 installed successfully.", "Update Complete", "success");
        //
        return $this->index();
    }

    public function purge() {

        $message = "The Cache, as well as all current sessions have now been clear";
        $messageType = "success";

        $database = \Library\Database::getInstance();
        if (!$database->query("TRUNCATE ?session", TRUE)) {
            $message = $database->getError();
            $messageType = "error";
        }
        //die;
        $this->alert($message, "", $messageType);
        return $this->returnRequest();
    }

    public function reset() {

        $fileHandler = \Library\Folder\Files::getInstance();
        $database = \Library\Database::getInstance();
        $usersdir = FSPATH . $this->config->getParam('site-users-folder', 'users');
        $databasename = FSPATH . $this->config->getParam('name', '', 'database');

        //Empty the users folder
        if (!$fileHandler->deleteContents($usersdir, array(),array(".htaccess"))) {
            $message = "Could not empty the user directory: {$usersdir}";
            $messageType = "error";
            $this->alert($message, "", $messageType);
            //$this->returnRequest();
        } 
       //recreate users folder
        //$fileHandler->create($usersdir);
        
        //Delete the setup ini file;
        if (!$fileHandler->delete(FSPATH . "config" . DS . "setup.ini")) {
            $message = "Could not delete your setup.ini file";
            $messageType = "error";
            $this->alert($message, "", $messageType);
            $this->returnRequest();
        }
        
        //Clear all sessions;
        if (!$database->query("TRUNCATE ?session", TRUE)) {
            $message = $database->getError();
            $messageType = "error";
            $this->alert($message, "", $messageType);
            //$this->returnRequest();
        }
        //die;

        $message = "We have deleted your setup.ini. You will have to manually delete your backedup database: {$_databasename}."
                . "Please Note you cannot re-install to the same database";
        $messageType = "success";

        $this->alert($message, "", $messageType);

        $this->redirect("/setup/install/step1"); //This will take them to installer
    }

}