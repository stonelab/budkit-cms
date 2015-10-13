<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 23/09/15
 * Time: 23:22
 */

namespace Budkit\Cms\Helper;

use Budkit\Dependency\Container;
use Budkit\Routing\Controller;
use Whoops\Exception\Inspector;
use Whoops\Handler\Handler;
use Whoops\Handler\HandlerInterface;
use Whoops\Run;

class ErrorHandler implements HandlerInterface
{

    protected $exception;

    public function __construct(Container $application)
    {

        $this->application = $application;

    }


    //WHOOPS INTERFACE METHODS
    public function handle()
    {

        $view = $this->application->createInstance("view",
            [
                $this->application->response,
                $this->application->createInstance("viewengine", [$this->application->response])
            ]
        );

        $view->setData("title", "An Error Occured");

        //Lets just show a pretty error page for now
        $this->application->response->send(  $view->render("errors/error") );
        //$this->response->send($this->view->render("errors/error"));

        //Don't execute any more handlers
        return Handler::LAST_HANDLER;

    }


    public function setRun(Run $run)
    {
    }

    public function setInspector(Inspector $inspector)
    {
    }

    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
    }

}