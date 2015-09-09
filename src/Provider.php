<?php

namespace App\Application;

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

        Route::setTokens(array('format' => '(\.[^/]+)?'));

        //Inbox, an inbox is a collection of messages and/or notifications streams!
        Route::attachResource("/inbox", "Inbox"); //a collection of streams;
        Route::attachResource("/message", "Message"); //controller should extend post;
        Route::attachResource("/notification", "Notification");
        Route::attachResource("/note", "Note"); //notes?
        Route::attachResource("/event", "Event"); //multiple event types and status, e.g proposed meting
        Route::attachResource("/stream", "Stream"); //collection of resources,
        Route::attachResource("/person", "Person"); //persons have different roles?
        Route::attachResource("/group", "Group"); //collection of persons?
        Route::attachResource("/file", "File"); //collection of persons?

    }

    public function definition() {
        return ["app.register" => "onRegister"];
    }
}