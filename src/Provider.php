<?php

namespace Budkit\Cms;

use Budkit\Cms\Helper\Authorize\Permission;
use Budkit\Application\Support\Service;
use Budkit\Dependency\Container;
use Budkit\Cms\Controller;
use Budkit\Cms\Helper\ErrorHandler;
use Budkit\Event\Event;
use Route;


class Provider implements Service
{

    protected $application;

    public function __construct(Container $application)
    {

        $this->application = $application;


    }

    public static function  getPackageDir()
    {
        return __DIR__ . "/";
    }

    public function onRegister()
    {
        //Register a before dispatch method to check if
        //The system has been installed;
        $this->application->observer->attach([$this, "onAfterRouteMatch"], "Dispatcher.afterRouteMatch");
        $this->application->observer->attach([$this, "onCompileLayoutData"], "Layout.onCompile.scheme.data");
        //$this->application->observer->attach([$this, "onRegisterThemes"], "app.register.themes");
        /*
        |--------------------------------------------------------------------------
        | Error Pages
        |--------------------------------------------------------------------------
        |
        | We need to modify the internal error handling, so we don't show system
        | inners to the entire world
        | - Check the nature of the environment. If development or test mode, leave as is
        | - If in production mode, then customize as follows
        |
        */
        $application = $this->application;
        $environment = $this->application->config->get("setup.environment.mode", 0);

        if ((int)$environment > 2){ //0=developermet;1=test;2=production
            //$this->application->error->unregister();
            $this->application->error->pushHandler( $application->createInstance( ErrorHandler::class ));
            //$this->application->error->register();
        }

       //print_R( $this->application->request->getAttributes() );
        //$this->view->appendLayoutSearchPath( Provider::getPackageDir()."layouts/");

        $this->instantiateHelpers();
        $this->registerRoutes();


    }

    public function registerRoutes(){

        //Sets global tokens
        Route::setTokens(['format' => '(\.[^/]+)?', 'page'=>'(\d)']);


        /*
        |--------------------------------------------------------------------------
        | The Homepage
        |--------------------------------------------------------------------------
        |
        | The installation route accepts POST so enable all methods
        |
        */
        Route::addGet("/", "homepage", Controller\Page::class);



        /*
        |--------------------------------------------------------------------------
        | Generic resources.
        |--------------------------------------------------------------------------
        |
        | Additional permissions will need to be set via the admin console to enable
        | or deny fine grained access to these resources;
        |
        */
        Route::attachResource("/page", Controller\Page::class); //a collection of streams;
        //Route::attachResource("/message", Controller\Message::class); //controller should extend post;
        Route::attachResource("/notification", Controller\Notification::class);
        Route::attachResource("/post", Controller\Post::class); //notes?
        Route::attachResource("/event", Controller\Event::class); //multiple event types and status, e.g proposed meting
        Route::attachResource("/stream", Controller\Stream::class); //collection of resources,
        Route::attachResource("/group", Controller\Group::class); //collection of persons?
        Route::attachResource("/file", Controller\File::class); //collection of persons?

        /*
        |--------------------------------------------------------------------------
        | Admin Routes
        |--------------------------------------------------------------------------
        |
        | All member actions
        |
        */
        Route::attach("/admin",  Controller\Admin::class, function($route){

            $route->setTokens(array(
                'format' => '(\.[^/]+)?'
            ));

            //subroutes
            $route->addGet('{format}', 'index');
            $route->addGet('/dashboard{format}', 'index');

            $route->attach('/pages', Controller\Admin\Pages::class, function($route){

                $route->addGet("{format}{/page}", "index");

            });

            /*
            |--------------------------------------------------------------------------
            | Members management
            |--------------------------------------------------------------------------
            */
            $route->attach('/members', Controller\Admin\Members::class, function ($route) {

                //$route->setAction(Controller\Admin\Settings\Permissions::class);
                $route->addGet('{format}', 'index');

                /*
                |--------------------------------------------------------------------------
                | Access Control settings
                |--------------------------------------------------------------------------
                */
                $route->attach('/permissions', Controller\Admin\Settings\Permissions::class, function ($route) {

                    //$route->setAction(Controller\Admin\Settings\Permissions::class);
                    $route->addGet('{format}', 'index');
                    $route->addPost('/rule', 'updaterule');
                    $route->addPost('/authority', 'updateauthority');


                });

            });

            $route->attach("/settings", Controller\Admin\Settings::class, function ($route) {

                $route->setTokens(array(
                    'format' => '(\.[^/]+)?'
                ));


                /*
                |--------------------------------------------------------------------------
                | Save global settings
                |--------------------------------------------------------------------------
                */
                $route->addPost('/save{format}', 'save');

                /*
                |--------------------------------------------------------------------------
                | Global System Configuration
                |--------------------------------------------------------------------------
                */
                $route->addGet('/configuration{format}', 'index');

                /*
                |--------------------------------------------------------------------------
                | Server settings settings
                |--------------------------------------------------------------------------
                */
                $route->attach('/server', Controller\Admin\Settings\Server::class, function ($route) {

                    //$route->setAction(Controller\Admin\Settings\Permissions::class);
                    $route->addGet('{format}', 'index');


                });
                /*
                |--------------------------------------------------------------------------
                | Input settings settings
                |--------------------------------------------------------------------------
                */
                $route->attach('/input', Controller\Admin\Settings\Input::class, function ($route) {

                    //$route->setAction(Controller\Admin\Settings\Permissions::class);
                    $route->addGet('{format}', 'index');


                });
                /*
                |--------------------------------------------------------------------------
                | Language settings
                |--------------------------------------------------------------------------
                */
                $route->attach('/localization', Controller\Admin\Settings\Localization::class, function ($route) {

                    //$route->setAction(Controller\Admin\Settings\Permissions::class);
                    $route->addGet('{format}', 'index');


                });


                /*
                |--------------------------------------------------------------------------
                | Email Settings
                |--------------------------------------------------------------------------
                */
                $route->attach('/emails', Controller\Admin\Settings\Emails::class, function ($route) {

                    //$route->setAction(Controller\Admin\Settings\Permissions::class);
                    $route->addGet('{format}', 'index');


                });

                /*
                |--------------------------------------------------------------------------
                | Maintenance settings
                |--------------------------------------------------------------------------
                */
                $route->attach('/maintenance', Controller\Admin\Settings\Maintenance::class, function ($route) {

                    //$route->setAction(Controller\Admin\Settings\Permissions::class);
                    $route->addGet('{format}', 'index');


                });


                /*
                |--------------------------------------------------------------------------
                | Maintenance settings
                |--------------------------------------------------------------------------
                */
                $route->attach('/appearance', Controller\Admin\Settings\Appearance::class, function ($route) {

                    //$route->setAction(Controller\Admin\Settings\Permissions::class);
                    $route->addGet('{format}', 'index');


                });

                /*
                |--------------------------------------------------------------------------
                | Navigation settings
                |--------------------------------------------------------------------------
                */
                $route->attach('/navigation', Controller\Admin\Settings\Navigation::class, function ($route) {

                    //$route->setAction(Controller\Admin\Settings\Permissions::class);
                    $route->addGet('{format}', 'index');


                });


                /*
                |--------------------------------------------------------------------------
                | Extensions settings
                |--------------------------------------------------------------------------
                */
                $route->attach('/extensions', Controller\Admin\Settings\Extensions::class, function ($route) {

                    //$route->setAction(Controller\Admin\Settings\Permissions::class);
                    $route->addGet('{format}', 'index');


                });

            });

        });




        /*
        |--------------------------------------------------------------------------
        | The installation wizard
        |--------------------------------------------------------------------------
        |
        | The installation route accepts POST so enable all methods
        |
        | NOTE: This has to be a separate route because it is a named route that is
        |       used by permissions to check install paths
        |
        */
        Route::add("/admin/setup/install{/step}", "install.admin", Controller\Admin\Setup\Install::class);


        /*
        |--------------------------------------------------------------------------
        | Member Routes
        |--------------------------------------------------------------------------
        |
        | All member actions
        |
        */

        Route::attach("/member", Controller\Member::class, function ($route) {
            $route->setTokens(array(
                'id' => '(\d+):[a-zA-Z0-9-_]+?', //username and userId
                'format' => '(\.[^/]+)?'
            ));

            $route->add('/signin{format}', 'signin');
            $route->add('/signup{format}', 'signup');
            $route->add('/signout{format}', 'signout');
            $route->add('/signin/reset', 'resetPassword');
            $route->addPost('/create{format}', 'add');
            $route->addGet('{/id}{format}', "view");
            $route->add('{/id}/edit{format}', "edit");
            $route->addDelete('{/id}/delete{format}', "delete");

            //Member settings
            $route->attach("/settings", Controller\Member\Settings::class, function($route){
                $route->addGet("{/group}{format}", 'index');
            });


            //Member settings
            $route->attach("/profile", Controller\Member\Profile::class, function($route){
                $route->addGet("{/id}{format}", 'index');
            });


            $route->attach("/timeline", Controller\Member\Timeline::class, function($route){

                $route->setTokens(array(
                    'id' => '(\d+)[a-zA-Z0-9-_]+?', //post I'ds must start with a number
                    'format' => '(\.[^/]+)?'
                ));

                $route->addPost('/put', 'put');
                $route->addGet("{format}", 'index');
                $route->addGet('{/id}{format}', "read");
                $route->add('/filters{format}', "filters");
                $route->add('/map{/id}{format}', "map");

                $route->attach("/filter", Controller\Member\Timeline\Filters::class, function($route){
                    $route->setTokens(array(
                        'name' => '[a-zA-Z0-9-_]+?',
                        'format' => '(\.[^/]+)?'
                    ));
                    $route->addPost('/new', 'add');
                    $route->addGet('{/name}{format}', "read");
                    $route->add('{/name}/edit{format}', "edit");
                    $route->addDelete('{/name}/delete{format}', "delete");

                });
            }); //a collection of streams;
        });

    }


    public function instantiateHelpers(){

        /*
        |--------------------------------------------------------------------------
        | Attach the mailer to the application container
        |--------------------------------------------------------------------------
        |
        | Mailer used by the CMS is powered by Nette/Mail
        |
        */
        $this->application->shareInstance(
            $this->application->createInstance( Helper\Mailer::class), 'mailer');

    }


    public function onAfterRouteMatch($afterRouteMatch)
    {
        //$response = $afterRouteMatch->getData('response');
        $router = $this->application->router;
        $request = $this->application->request;
        $response = $this->application->response;
        $config = $this->application->config;


        $installRoute = $router->getRoute("install.admin");

        if (!$config->get("setup.database.installed")) {


            /*
            |--------------------------------------------------------------------------
            | If the database is not "installed" it means the app has not been setup.
            |--------------------------------------------------------------------------
            |
            | Redirect to the install script;
            |
            */
            //If the database is not installed and we are trying to access another route
            //redirect to the installer;
            if (!$installRoute->isRequestMatch($request)) {
                $this->application->dispatcher->redirect("/admin/setup/install");
            }

        } else {
            //If the database is installed and we are trying to access the installer


            /*
            |--------------------------------------------------------------------------
            | If trying to go back to the install ptah
            |--------------------------------------------------------------------------
            |
            | No you can't install an app that is already installed
            |
            */
            if ($installRoute->isRequestMatch($request)) {
                $this->application->dispatcher->redirect("/");
            }

            /*
            |--------------------------------------------------------------------------
            | Does the user have permission to view this current path?
            |--------------------------------------------------------------------------
            |
            | Check that the current user has permission to follow this route
            |
            */
            $permission = $this->application->createInstance(Permission::class);

            if (!$permission->isAllowedRoute($router->getMatchedRoute(), $request)) {

                $message = t("You do not have permission to access the requested resource. If you are not signed in please consider signing in with an account that has sufficient permissions.");

//                $response->setStatusCode( HTTP_NOT_ALLOWED );
//                $response->setStatusMessage( $message );
//                $response->addContent( $message );
//                $response->send();

                //Store the intercepted path, in case we can redirect to it later on.
                $session = $this->application->session;
                $session->set("interceptedPath", $request->getPathInfo(), "default");

                $response->addAlert($message, "warning");


                //@TODO maybe redirect to a public page or just post a message
                //exit("You are not allowed to view this resource");

                $this->application->dispatcher->redirect("/member/signin", HTTP_FOUND, $message);

            }
        }
    }

    public function onCompileLayoutData($event)
    {

        $scheme = $event->getData("scheme");
        $path = $event->getData("path");

        if (strtolower($scheme) == "config") {

            //if the scheme is config://get.config.path, then load the config data;
            return $event->setResult(trim($this->application->config->get($path)));
        }

    }


//    public function onRegisterThemes($event){
//
//        $themes     = $event->getResult();
//        $themes[]   = [
//            "provider" => "budkit/cms",
//            "name"  => "default",
//            "source"=> $this->getPackageDir()."Themes/default"
//        ];
//
//        //Check if no default themes have been set and set budkit/cms as default;
//        $provider = $this->application->config->get("design.theme.provider", "budkit/cms");
//        $theme  = $this->application->config->get("design.theme.name", "default");
//
//        //$event      = new Event("App.init.themes", $this);
//        $event->setResult( $themes ); //all members who call this even need to append to the result;
//
//    }

    public function definition()
    {
        return [
            "app.register" => "onRegister"
        ];
    }
}