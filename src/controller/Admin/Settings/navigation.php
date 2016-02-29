<?php

namespace Budkit\Cms\Controller\Admin\Settings;
use Budkit\Cms\Controller\Admin\Settings;
use Budkit\Cms\Helper\Menu;

/**
 * Action controller for managing system appearance 
 *
 * This class implements an interface for experessing admin defined system appearance
 * settings. 
 *
 * @category  Application
 * @package   Action Controller
 * @license   http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version   1.0.0
 * @since     Jan 14, 2012 4:54:37 PM
 * @author    Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 */
class Navigation extends Settings {


    public function index($format = 'html', $id="") {


        $this->view->setData("title", t("Navigation"));


        $menus = $this->application->createInstance(Menu::class)->getMenus();
        $this->view->setData("menus", $menus);

        $this->view->addToBlock("main", "import://admin/settings/navigation");
        $this->view->setLayout("member/dashboard");

    }


    public function create($format = 'html'){

        //1. Load the model
        $menu = $this->application->createInstance( Menu::class);

        //2. If we are editing the authority, save
        if ($this->application->input->methodIs("post")):

            $menuid    = $this->application->input->getInt('menu_group_id', '', 'post');

            $data = [
                "menu_group_uid" => $this->application->input->getString('menu_group_uid', '', 'post'), //usernameid will only be obtained from POST data,
                "menu_group_title"=>$this->application->input->getString('menu_group_title', '', 'post'), //unsernamepassword will only be obtained from POST data
            ];

            if(!empty($menuid)){
                $data['menu_group_id'] = intval($menuid ) ;
            }

            if(!empty($data['menu_group_uid'])){
                $data['menu_group_uid'] =str_replace(array(" "), "_", $data['menu_group_uid']);
            }

            //try save menu
            if (empty($data["menu_group_uid"])|| empty($data["menu_group_title"]) || !$menu->saveMenu( $data, empty($menuid)? false : true  )  ) {

                $this->response->addAlert( _("We had a problem creating your menu"), "error");
            } else {
                $this->response->addAlert(_("The menu was created successfully"),  "success");
            }

        endif;

        //Report on state saved
        $this->application->dispatcher->redirect ($this->application->input->getReferer(), HTTP_FOUND, null);

        return true;

    }

    public function add($format = 'html'){

        $menu   = $this->application->createInstance( Menu::class);
        $update = true;
        //2. If we are editing the authority, save
        if ($this->application->input->methodIs("post")):

            $data = [
                "menu_title" => $this->application->input->getString("menu_title", "", "post"),
                "menu_url" => $this->application->input->getString("menu_url","","post"),
                "menu_group_id" => $this->application->input->getInt("menu_group_id","","post"),
                "menu_parent_id" => $this->application->input->getInt("menu_parent_id", "" ,"post"),
                "menu_classes" => $this->application->input->getString("menu_classes","","post"),
                "menu_id" => $this->application->input->getInt("menu_id","","post"),
            ];

            if(empty($data["menu_id"])){
                $update = false;
                unset($data["menu_id"]);
            }

            //try save menu
            if (empty($data["menu_title"])|| empty($data["menu_url"])|| empty($data["menu_group_id"]) || !$menu->saveLink( $data, $update )  ) {

                $this->response->addAlert( _("We had a problem adding your link to the menu"), "error");
            } else {
                $this->response->addAlert(_("The menu link was updated successfully"),  "success");
            }

        endif;


        //Report on state saved
        $this->application->dispatcher->redirect ($this->application->input->getReferer(), HTTP_FOUND, null);

        return true;
    }

    /**
     * @param string $format
     */
    public function update($group = '', $link='', $format = 'html'){

        echo 'update menu group or link';
    }

    /**
     * Delete a menu group or menu item
     *
     * @param $id
     * @param string $type
     * @param string $format
     */
    public function delete($group = '', $link = '', $format ='html'){
        echo 'delete menu group or link';
    }
}

