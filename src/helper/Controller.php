<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 23/09/15
 * Time: 23:22
 */

namespace Budkit\Cms\Helper;

use Budkit\Cms\Model\User;
use Budkit\Cms\Provider;
use Budkit\Event\Event;
use Budkit\Routing\Controller as RouteController;
use Budkit\Dependency\Container as Application;


class Controller extends RouteController {


    protected $user;



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
        | The Current User
        |--------------------------------------------------------------------------
        |
        | Creates an instance of the User glass. to make sure you are always using
        | the current user, user $this->user->getCurrentUser()
        |
        */
        $this->user = new User($this->application, $this->application->database, $this->application->session);

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
        | Attach custom post Events
        |--------------------------------------------------------------------------
        |
        | This is for adding new actions to the new post button. Actions should be
        | registered before this view is called!
        |
        */
        $loadPostExtensions = new Event("Layout.load.post.extensions");

        $this->observer->trigger( $loadPostExtensions );

        $this->view->setData("newactions", $loadPostExtensions->getResult());

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

        //Extend the user menu
        $this->observer->attach([$menu, "extendDashboardMenu"], "Layout.onCompile.menu.data");

        //Extend the media menu
        $this->observer->attach([$menu, "extendMediaMenu"], "Layout.onCompile.menu.data");

        //Check has permission
        $this->observer->attach([$menu, "hasPermission"], "Layout.beforeRender.menu.item");


    }


    /**
     *
     * //Do not check again for view as this is already checked by the provider;
     *
     * @param string $level
     * @param string $path
     * @return bool
     */
    function checkPermission( $level="view", $path = ''){

        $path = empty($path)? $this->request->getPathInfo() : $path;
        $route = $this->application->router->getMatchedRoute();

        //print_R($route); die;


        if (!$this->permission->isAllowedRoute( $route, $this->request, $level)) {

            $message = t("You do not have permission to access the requested resource. If you are not signed in please consider signing in with an account that has sufficient permissions.");

//                $response->setStatusCode( HTTP_NOT_ALLOWED );
//                $response->setStatusMessage( $message );
//                $response->addContent( $message );
//                $response->send();

                //store the intercepted Path so we can check after login
                $session = $this->application->session;
                $session->set("interceptedPath", $path, "default");
                //$session->update( $session->getId() );

                //$session->update( $session->getId() );

            //print_R($session); die;

            $this->response->addAlert($message, "warning");


            //@TODO maybe redirect to a public page or just post a message
            //exit("You are not allowed to view this resource");

            $this->application->dispatcher->redirect("/member/signin", HTTP_FOUND, $message, $this->response->getAlerts());

            return false;

        }

    }


}