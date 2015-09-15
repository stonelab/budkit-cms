<?php

namespace Budkit\Cms;

use Budkit\Application\Support\Service;
use Budkit\Dependency\Container;

class Provider implements Service {

    protected $application;

    public function __construct(Container $application) {
        $this->application = $application;
    }

    public static function  getPackageDir(){
        return __DIR__."/";
    }

    public function onRegister() {

        \Route::setTokens(array('format' => '(\.[^/]+)?'));

        //Inbox, an inbox is a collection of messages and/or notifications streams!
        \Route::attachResource("/inbox", "Budkit\\Cms\\Controller\\Inbox"); //a collection of streams;
        \Route::attachResource("/message", "Budkit\\Cms\\Controller\\Message"); //controller should extend post;
        \Route::attachResource("/notification","Budkit\\Cms\\Controller\\Notification");
        \Route::attachResource("/note", "Budkit\\Cms\\Controller\\Note"); //notes?
        \Route::attachResource("/event", "Budkit\\Cms\\Controller\\Event"); //multiple event types and status, e.g proposed meting
        \Route::attachResource("/stream", "Budkit\\Cms\\Controller\\Stream"); //collection of resources,
        \Route::attachResource("/person", "Budkit\\Cms\\Controller\\Person"); //persons have different roles?
        \Route::attachResource("/group", "Budkit\\Cms\\Controller\\Group"); //collection of persons?
        \Route::attachResource("/file", "Budkit\\Cms\\Controller\\File"); //collection of persons?

    }

    public function definition() {
        return ["app.register" => "onRegister"];
    }
}