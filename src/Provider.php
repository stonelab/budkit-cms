<?php

namespace Budkit\Cms;

use Budkit\Cms\Helper\Authorize\Permission;
use Budkit\Application\Support\Service;
use Budkit\Dependency\Container;
use Budkit\Cms\Controller;
use Budkit\Protocol\Uri;
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

        //Sets global tokens
        Route::setTokens(['format' => '(\.[^/]+)?', 'id' => '(\d+)([a-zA-Z0-9-_]+)?']);

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
        Route::attachResource("/message", Controller\Message::class); //controller should extend post;
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
        Route::addGet("/admin", "admin", Controller\Admin::class);
        Route::addGet("/admin/pages", "admin.pages", Controller\Admin\Pages::class);

        Route::attach("/admin/settings", Controller\Admin\Settings::class, function ($route) {

            $route->setTokens(array(
                'format' => '(\.[^/]+)?'
            ));


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
            | Access Control settings
            |--------------------------------------------------------------------------
            */
            $route->attach('/permissions', Controller\Admin\Settings\Permissions::class, function ($route) {

                //$route->setAction(Controller\Admin\Settings\Permissions::class);
                $route->addGet('{format}', 'index');
                $route->addPost('/rule', 'updaterule');
                $route->addPost('/authority', 'updateauthority');


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


        });

        //The installation route accepts POST so enable all methods
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
                'id' => '(\d+)[a-zA-Z0-9-_]+?',
                'format' => '(\.[^/]+)?'
            ));
            //subroutes
            $route->addGet('/dashboard{format}', 'index');
            $route->add('/signin{format}', 'signin');
            $route->add('/signup{format}', 'signup');
            $route->add('/signout{format}', 'signout');
            $route->add('/signin/reset', 'resetPassword');
            $route->addPost('/create{format}', 'add');
            $route->addGet('{/id}{format}', "view");
            $route->add('{/id}/edit{format}', "edit");
            $route->addDelete('{/id}/delete{format}', "delete");

            $route->add('/settings{/group}{format}', Controller\Member\Settings::class);
            $route->add("/account", "member.account", Controller\Member\Account::class);
            $route->addGet("/posts", "member.posts", Controller\Member\Posts::class);
            $route->add("/profile", "member.profile", Controller\Member\Profile::class);
            $route->addGet("/messages", "member.messages", Controller\Member\Inbox::class); //a collection of streams;

        });


        //Register a before dispatch method to check if
        //The system has been installed;
        $this->application->observer->attach([$this, "onAfterRouteMatch"], "Dispatcher.afterRouteMatch");
        $this->application->observer->attach([$this, "onCompileLayoutData"], "Layout.onCompile.scheme.data");
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
                $response->addAlert($message, "warning");


                //@TODO maybe redirect to a public page or just post a message
                //exit("You are not allowed to view this resource");

                $this->application->dispatcher->redirect("/member/signin", HTTP_FOUND, $message, $response->getAlerts());

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

        //die;

    }

    public function definition()
    {
        return [
            "app.register" => "onRegister",
        ];
    }
}