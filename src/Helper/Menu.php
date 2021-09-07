<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 23/09/15
 * Time: 23:38
 */

namespace Budkit\Cms\Helper;

use Budkit\Cms\Helper\Authorize\Permission;
use Budkit\Cms\Model\Media\Content;
use Budkit\Cms\Model\User;
use Budkit\Datastore\Database;
use Budkit\Dependency\Container;
use Budkit\Event\Event;
use Budkit\Protocol\Http\Request;
use Budkit\Protocol\Uri;


class Menu
{

    protected $database;


    public function __construct(Container $Application , Database $database, Permission $permission, Uri $uri, Request $request, User $user)
    {
        $this->database = $database;
        $this->permission = $permission;
        $this->uri = $uri;
        $this->request = $request;
        $this->user = $user;
        $this->application = $Application;

    }

    public function getMenus()
    {
        //1. Get all menu items for this menu id from the table
        $statement = $this->database
            ->select("g.*")
            ->from("?menu_group AS g")
            ->orderBy("g.menu_group_id", "ASC")->prepare();

        $results = $statement->execute();
        $nodes = array();

        while ($menu = $results->fetchArray()) {

            $menu['children'] = $this->getMenuArray($menu['menu_group_uid'], false);
            $nodes[] = $menu;

        }
        return $nodes;
    }

    public function getMenuArray($uniqueId, $hierarchical = true, $compiled = false)
    {

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
            //
            $parent = $menu['menu_parent_id'];
            $id = $menu['menu_id'];

            //
            if (!empty($parent)){
                $menu['indent'] = sizeof($right);

            }
            $right[] = $menu['rgt'];


            //If we want this menu as a heirachy
            if ($hierarchical) {
                if (array_key_exists($parent, $nodes)) {
                    $nodes[$parent]["children"][$id] = $menu;
                } else {
                    $nodes[$id] = $menu;
                }

            } else {

                $nodes = insertIntoArray($nodes, $parent, $id, $menu, true, true);
            }
        }

        return $nodes;
    }


    public function getCompiledMenuArray($uniqueId, $hierarchical=true, $authorise = false){


        $menuItems = $this->getMenuArray($uniqueId, $hierarchical, true);

        //Use this event to extend the loaded menu.
        //beforeCompile.menu.data should not really be used.
        //Will need to think of private events
        $menuItemsExtendEvent = new Event('Layout.onCompile.menu.data', $this, ["uid" => $uniqueId]);
        $menuItemsExtendEvent->setResult($menuItems);

        $this->application->observer->trigger($menuItemsExtendEvent); //Parse the Node;

        $menuItems = $menuItemsExtendEvent->getResult();

        $menuItems = $this->beforeRenderMenuItem( $menuItems );


        return $menuItems;

    }


    private function beforeRenderMenuItem($menuItems){

        $nodes = array();
        
        foreach($menuItems as $key => $item){

            if (!empty($item['menu_url'])):

                //check has permission;
                $menuItemRenderEvent = new Event('Layout.beforeRender.menu.item', $this, ["item" => $item]);
                $this->application->observer->trigger($menuItemRenderEvent); //Parse the Node;

                $item = $menuItemRenderEvent->getResult();

                if (!isset($item['menu_viewable']) || !$item['menu_viewable']) {
                    continue;
                }

                //Loop through the children.
                if(!empty($item['children'])){
                    $item['children'] = $this->authoriseMenu( $item['children'] );
                }

                //affix the rendered menu item here
                $nodes[$key] = $item ;

            endif;

        }

        return $nodes;
    }

    public function authoriseMenu( $menuItem ){
        return $menuItem;
    }

    public function load($event)
    {
        $uniqueId = $event->getData("uid");
        $nodes = $this->getMenuArray($uniqueId);

        $event->setResult($nodes);

    }

    /**
     * Saves or updates;
     *
     * @param array $data
     * @return bool
     */
    public function saveMenu(array $data, $update = true)
    {

        $database = $this->database;
        $data = array_map(function ($value) use ($database) {
            return $database->quote($value);
        }, $data);

        if ($this->database->insert("?menu_group", $data, $update)) {
            return true;
        }

        return false;
    }


    public function saveLink(array $data )
    {

        //3. Load and prepare the  Menu Table
        $table = $this->database->getTable("?menu");
        $database = $this->database;
        $parentId   = intval($data['menu_parent_id']);

//        $data = array_map(function ($value) use ($database) {
//            return $database->quote($value);
//        }, $data);


        if (!$table->bindData($data)) {

            throw new \Exception($table->getError());
            return false;
        }


        //4. Are we adding a new row
        if ($table->isNewRow() ) {

            //Get the parent left and right value, to make space
            if( !empty($parentId) ) {

                $parent = $this->database->select("lft, rgt, menu_id")->from("?menu")->where("menu_id", (int)$table->getRowFieldValue('menu_parent_id'))->prepare()->execute()->fetchObject();

                //$this->database->where(array("menu_parent_id" => $parent->menu_id) )->orWhere( array("menu_id"=> $parent->menu_id) )->where(array("lft >" => ($parent->rgt )))->update("?menu", array("lft" => "lft+2"));
                $this->database->where(array("menu_parent_id" => $parent->menu_id) )->orWhere( array("menu_id"=> $parent->menu_id) )->where(array("rgt >=" => ($parent->rgt )))->update("?menu", array("rgt" => "rgt+2"));

            }

            $table->setRowFieldValue("lft", !empty($parentId) ? $parent->rgt : "1");
            $table->setRowFieldValue("rgt",  !empty($parentId) ? $parent->rgt + 1 : "2");
        }

        //5. Save the table modifications
        if (!$table->save()) {
            return false;
        }


       // print_R($this->database); die;

        return true;

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
                    "menu_title" => "Profile",
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
                    "menu_url" => "/auth/signout",
                )
            );
        } else {
            array_push($menuItems, array(
                    "menu_title" => "Sign In",
                    "menu_url" => "/auth/signin",
                )
            );
        }
        $event->setResult($menuItems);
    }

    public function extendDashboardMenu($event)
    {


        $menuId = $event->getData("uid");
        $menuItems = $event->getResult();


        if($menuId == "mediamenu"){

            array_unshift($menuItems, [
                    "menu_title" => "Everything",
                    "menu_classes" => "link-label",
                    "menu_url" => "/member/timeline"
                ],
                [
                    "menu_title" => "@ Mentions",
                    "menu_classes" => "link-label",
                    "menu_url" => "/member/timeline/mentions"
                ]
            );

            return $event->setResult($menuItems);
        }

        //We only process the usermenu;
        if ($menuId !== "dashboardmenu") return;


        //$menuUser = $this->user->getCurrentUser();

        //will need to load this from the DB!
        $labels = [
            [
                "menu_title" => "information",
                "menu_classes" => "link-label",
                "menu_url" => "/member/timeline/information"
            ],
            [
                "menu_title" => "urgent",
                "menu_classes" => "link-label",
                "menu_url" => "/member/timeline/urgent"
            ],
            [
                "menu_title" => "task",
                "menu_classes" => "link-label",
                "menu_url" => "/member/timeline/task"
            ],
            [
                "menu_title" => "done",
                "menu_classes" => "link-label",
                "menu_url" => "/member/timeline/done"
            ],
            [
                "menu_title" => "Labels...",
                "menu_classes" => "link-label modal-response",
                "menu_url" => "/member/timeline/list"
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


    public function extendMediaMenu($event){


        $menuId = $event->getData("uid");
        $menuItems = $event->getResult();


        if (preg_match("/^mediamenu:(\\d+[a-zA-Z0-9]{9})/", $menuId, $matches)) {

            $postid = $matches[1];

            $content = $this->application->createInstance( Content::class );
            $post    = $content->loadObjectByUri( $postid , ['media_owner'] ); //just one property

            //If this is a post!
            if(!empty($post->getObjectType())) {

                $actions = [

                    array(
                        "menu_title" => "Report",
                        "menu_url" => "/post/{$postid}/report",
                    )
                ];

                if( $this->user->getPropertyValue("user_name_id") == $post->getPropertyValue("media_owner") ){

                    $actions = array_merge( $actions, [
                        array(
                            "menu_title" => "Label",
                            "menu_url" => "/post/{$postid}/label",
                            "menu_attributes" => [
                                "data-action" => "add-label",
                                "data-target" => $postid
                            ],
                            'children' => [ //@TODO limit labels to max 5 per user
                                [
                                    "menu_title" => "#information",
                                    "menu_url" => "/post/{$postid}/label/information",
                                    "menu_classes" => "menu-display-selected"
                                ],
                                [
                                    "menu_title" => "#done",
                                    "menu_url" => "/post/{$postid}/label/information",
                                    "menu_classes" => "menu-display-selected"
                                ],
                                [
                                    "menu_title" => "#task",
                                    "menu_url" => "/post/{$postid}/label/information",
                                    "menu_classes" => "menu-display-selected"
                                ],
                                [
                                    "menu_title" => "#success",
                                    "menu_url" => "/post/{$postid}/label/information",
                                    "menu_classes" => "menu-display-selected"
                                ],
                                [
                                    "menu_title" => "#priority",
                                    "menu_url" => "/post/{$postid}/label/information",
                                    "menu_classes" => "menu-display-selected"
                                ]
                            ]
                        ),
                        array(
                            "menu_title" => "Edit",
                            "menu_url" => "/post/{$postid}/data",
                        ),
                        array(
                            "menu_title" => "Delete",
                            "menu_url" => "/post/{$postid}/delete",
                            "menu_classes" => "color-alizarin"
                        )

                    ]);

                }

                $menuItems = array_merge($menuItems, $actions );
            }


            return $event->setResult($menuItems);

        }

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