<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 23/09/15
 * Time: 23:22
 */

namespace Budkit\Cms\Helper;

use Budkit\Cms\Provider;
use Budkit\Routing\Controller as RouteController;
use Budkit\Dependency\Container as Application;


class Controller extends RouteController {




    public function __construct(Application $application, Menu $menu) {


        /*
        |--------------------------------------------------------------------------
        | Construct the parent
        |--------------------------------------------------------------------------
        |
        | The parent requires us to pass the application container. However some
        | instances are not available in this container, such as config, sessions and
        | the database;
        |
        */

        parent::__construct($application);


        /*
        |--------------------------------------------------------------------------
        | The Permission handler
        |--------------------------------------------------------------------------
        |
        | The parent requires us to pass the application container. However some
        | instances are not available in this container, such as config, sessions and
        | the database;
        |
        */
        $this->permission = $application->createInstance( Authorize\Permission::class  );


        /*
        |--------------------------------------------------------------------------
        | Look for layouts in a layouts subfolder in this package
        |--------------------------------------------------------------------------
        |
        | Tells the View/Display class to look for additional layouts in the package
        | directory. Of course, these layouts too are overwritten when added to
        | /public/layouts
        |
        */
        $this->view->appendLayoutSearchPath( Provider::getPackageDir()."layouts/");



        /*
        |--------------------------------------------------------------------------
        | Attach custom events
        |--------------------------------------------------------------------------
        |
        | Lets just go ahead and attach as many hooks as we need which are not
        | executed now,but just tell the framework where to find more functionality
        | when the need arises. for example loading a menu from the database on, when
        | the "Layout.onCompile.menu.data" event is triggered
        |
        */
        $this->observer->attach([$menu, "load"], "Layout.beforeCompile.menu.data");

        //Extend the user menu
        $this->observer->attach([$menu, "extendUserMenu"], "Layout.onCompile.menu.data");

        //Check has permission
        $this->observer->attach([$menu, "hasPermission"], "Layout.beforeRender.menu.item");


    }


    function checkPermission( $level="view", $path = ''){

        $path = empty($path)? $this->request->getPathInfo() : $path;

        if (!$this->permission->isAllowed( $path, null, $level)) {

            $message = t("You do not have permission to access the requested resource. If you are not signed in please consider signing in with an account that has sufficient permissions.");

//                $response->setStatusCode( HTTP_NOT_ALLOWED );
//                $response->setStatusMessage( $message );
//                $response->addContent( $message );
//                $response->send();
            $this->response->addAlert($message, "warning");


            //@TODO maybe redirect to a public page or just post a message
            //exit("You are not allowed to view this resource");

            $this->application->dispatcher->redirect("/member/signin", HTTP_FOUND, $message, $this->response->getAlerts());

        }

    }


}