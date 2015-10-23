<?php

namespace Budkit\Cms\Helper;

use Budkit\Dependency\Container;
use React\EventLoop\LoopInterface;
use Thruway\Authentication\AbstractAuthProviderClient;
use Thruway\Logging\Logger;

class PubsubAuth extends  AbstractAuthProviderClient
{

    private $application;

    /**
     * Constructor
     *
     * @param array $authRealms
     * @param \React\EventLoop\LoopInterface $loop
     */
    public function __construct(Container $application, array $authRealms )
    {

        $this->application = $application;
        /*
         * Set authorization the realm. Defaults to "thruway.auth"
         *
         * This realm is only used between the Authentication Provider Client and the Authentication Manager Client on the server.
         *
         */
        parent::__construct($authRealms);

    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return 'budkit_cms'; //This is the authentication method name that you'll need to add to the client
    }


    /**
     * Process AuthenticateMessage
     * Check authenticate and return ["SUCCESS"] and ["FAILURE"]
     *
     * @param mixed $signature
     * @param mixed $extra
     * @return array
     */
    public function processAuthenticate($signature, $extra = null)
    {
        //@todo look inside this->application->session and load the token;
        //@todo check if the user session with this token is authenticated;
        //

        //Logger::debug($this, "budkit_cms authentication has commenced {$this->session->getCaller()}");

        //print_R($this);
        //print_R($signature);

        return ["SUCCESS"];

    }

}