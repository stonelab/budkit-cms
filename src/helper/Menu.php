<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 23/09/15
 * Time: 23:38
 */

namespace Budkit\Cms\Helper;

use Budkit\Cms\Model\User;
use Budkit\Datastore\Database;
use Budkit\Cms\Helper\Authorize\Permission;
use Budkit\Protocol\Http\Request;
use Budkit\Protocol\Uri;


class Menu{

    protected $database;


    public function __construct(Database $database, Permission $permission, Uri $uri, Request $request, User $user)
    {
        $this->database = $database;
        $this->permission = $permission;
        $this->uri = $uri;
        $this->request = $request;
        $this->user = $user;

    }


    public function load($event)
    {

        $uniqueId = $event->getData("uid");

        if (empty($uniqueId)) return; //we need a unique Id to load the menu;

        $parent = 0;
        $id = 0;

        //1. Get all menu items for this menu id from the table
        $statement = $this->database
            ->select("m.*")
            ->from("?menu m")
            ->join("?menu_group g", "m.menu_group_id=g.menu_group_id", "LEFT")
            ->where("g.menu_group_uid=", $this->database->quote($uniqueId, false))
            ->orderBy("m.lft", "ASC")->prepare();

        $results = $statement->execute();

        $nodes = array();
        $right = array();

        while ($menu = $results->fetchArray()) {
            //while($authority = $results->fetchAssoc()){
            $menu['children'] = array();
            $menu['indent'] = 0;

            //Now indent
            if (sizeof($right) > 0) {

                $lastrgt = end($right);
                $largestrgt = max($right);

                if ($menu['rgt'] > $lastrgt) {
                    array_pop($right);
                }
                if ($menu['rgt'] > $largestrgt) {
                    $right = array();
                }
            }
            $menu['indent'] = sizeof($right);
            $right[] = $menu['rgt'];

            $parent = $menu['menu_parent_id'];
            $id = $menu['menu_id'];

            if (array_key_exists($parent, $nodes)) {
                $nodes[$parent]["children"][$id] = $menu;
            } else {
                $nodes[$id] = $menu;
            }
        }


        $event->setResult($nodes);

    }

    public function extendUserMenu($event){

        $menuId    = $event->getData("uid");

        //We only process the usermenu;
        if($menuId !== "usermenu") return;

        $menuItems = $event->getResult();
        $menuUser  = $this->user->getCurrentUser();

        if( $menuUser->isAuthenticated() ){
            array_push($menuItems, array(
                    "menu_title" => "Sign out",
                    "menu_url" => "/member/signout",
                )
            );
            $event->setResult( $menuItems );
        }

    }

    public function hasPermission($event){

        $item = $event->getData("item");

        if (!is_array($item) || !isset($item["menu_url"])) return false; //we need a unique Id to load the menu;

        //Internalize the menu url
        $item["menu_url"] = $this->uri->internalize($item['menu_url']);


        //@TODO mandate that a permission is set for every menu item url created in the console, as routes cannot be used to chek permissions;

        if($this->permission->isAllowed($item["menu_url"])){

            $item["menu_viewable"] = true;
        }

        //Check whether the current menu is active;
        if($item["menu_url"] == $this->request->getPathInfo()){
            $item["menu_isactive"] = true;
        }


        $event->setResult( $item );
    }
}