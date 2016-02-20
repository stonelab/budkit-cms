<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 23/09/15
 * Time: 23:38
 */

namespace Budkit\Cms\Helper;

use Budkit\Cms\Helper\Authorize\Permission;
use Budkit\Cms\Model\User;
use Budkit\Datastore\Database;
use Budkit\Protocol\Http\Request;
use Budkit\Protocol\Uri;


class Menu
{

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

    public function extendUserMenu($event)
    {

        $menuId = $event->getData("uid");

        //We only process the usermenu;
        if ($menuId !== "usermenu") return;

        $menuItems = $event->getResult();
        $menuUser = $this->user->getCurrentUser();

        if ($menuUser->isAuthenticated()) {

            //will need to load this from the DB!
            $labels = [
                [
                    "menu_title" => "About you",
                    "menu_url" => "/member/settings/profile"
                ],
                [
                    "menu_title" => "Notifications",
                    "menu_url" => "/member/settings/notifications"
                ]
                //@TODO maybe a more page for extra settings?
            ];

            //merge the children;
            foreach ($menuItems as $id => $menuItem) {
                if ($menuItem["menu_url"] == "/member/settings") {
                    $menuItem['children'] = array_merge($menuItem['children'], $labels);
                    $menuItems[$id] = $menuItem;
                    break;
                }
                continue;
            }

            array_push($menuItems, array(
                    "menu_title" => "Sign out",
                    "menu_url" => "/member/signout",
                )
            );
        } else {
            array_push($menuItems, array(
                    "menu_title" => "Sign In",
                    "menu_url" => "/member/signin",
                )
            );
        }
        $event->setResult($menuItems);
    }

    public function extendDashboardMenu($event)
    {


        $menuId = $event->getData("uid");

        //We only process the usermenu;
        if ($menuId !== "dashboardmenu") return;

        $menuItems = $event->getResult();
        //$menuUser = $this->user->getCurrentUser();

        //will need to load this from the DB!
        $labels = [
            [
                "menu_title" => "Information",
                "menu_classes" => "link-label",
                "menu_url" => "/member/timeline/filter/Information"
            ],
            [
                "menu_title" => "Urgent",
                "menu_classes" => "link-label",
                "menu_url" => "/member/timeline/filter/Urgent"
            ],
            [
                "menu_title" => "Task",
                "menu_classes" => "link-label",
                "menu_url" => "/member/timeline/filter/Task"
            ],
            [
                "menu_title" => "Done",
                "menu_classes" => "link-label",
                "menu_url" => "/member/timeline/filter/Done"
            ],
            [
                "menu_title" => "More filters",
                "menu_classes" => "link-label",
                "menu_url" => "/member/timeline/filters"
            ]
        ];

        //merge the children;
        foreach ($menuItems as $id => $menuItem) {
            if ($menuItem["menu_url"] == "/member/timeline") {
                $menuItem['children'] = array_merge($menuItem['children'], $labels);
                $menuItems[$id] = $menuItem;
                break;
            }
            continue;
        }

        //print_R($menuItems);

        $event->setResult($menuItems);
    }

    public function hasPermission($event)
    {

        $item = $event->getData("item");

        if (!is_array($item) || !isset($item["menu_url"])) return false; //we need a unique Id to load the menu;

        //Internalize the menu url
        $item["menu_url"] = $this->uri->internalize($item['menu_url']);


        //@TODO mandate that a permission is set for every menu item url created in the console, as routes cannot be used to chek permissions;

        if ($this->permission->isAllowed($item["menu_url"])) {

            $item["menu_viewable"] = true;
        }

        //Check whether the current menu is active;
        if ($item["menu_url"] == $this->request->getPathInfo()) {
            $item["menu_isactive"] = true;
        }


        $event->setResult($item);
    }
}