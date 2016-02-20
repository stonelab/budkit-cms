<?php

namespace Budkit\Cms\Controller\Admin\Settings;

use Budkit\Cms\Controller\Admin\Settings;

/**
 * Admin action controller for managing system content 
 *
 * This class implements an interface for moderating user submitted content.. 
 *
 * @category  Application
 * @package   Action Controller
 * @license   http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version   1.0.0
 * @since     Jan 14, 2012 4:54:37 PM
 * @author    Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 */
class Emails extends Settings{

    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";

        // echo "Pages admin";
        $this->view->setData("title", t("Settings » Emails"));

        $this->view->setData("emails", $this->application->config->load("email"));

        $this->view->addToBlock("main", "import://admin/settings/emails");
        //$this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/dashboard");

    }
}

