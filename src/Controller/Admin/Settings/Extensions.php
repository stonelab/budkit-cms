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
class Extensions extends Settings {

    public function index($format = 'html', $id="") {
        //echo "Browsing in {$format} format";

        // echo "Pages admin";
        $this->view->setData("title", t("Extensions"));

        $this->view->addData("action", ["title"=>"Add Extension","link"=>"/admin/extension/create", "class"=>"btn-primary"]);

        //$this->view->setData("name", "Livingstone");
        $this->view->addToBlock("main", "import://admin/console/extensions");
        $this->view->setLayout("member/dashboard");

    }
}

