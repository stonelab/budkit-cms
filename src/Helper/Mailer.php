<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 28/01/2016
 * Time: 06:26
 */

namespace Budkit\Cms\Helper;

use Budkit\Dependency\Container;
use Budkit\Validation\Exception\InvalidException;
use Nette\Mail\Message;

//Nette
use Nette\Mail\IMailer as Handler;
use Nette\Mail\SendmailMailer;
use Nette\Mail\SmtpMailer;

class Mailer
{
    protected $message;
    protected $handler;


    public function __construct(Container $container)
    {

        $this->application = $container;
        $this->config = $container->config;

        //If config mode is smtp, use smtp mailer
        $modes= ["smtp", "sendmail"];
        $mode = $this->config->get("server.mail.outgoing-handler", "sendmail");
        //if config mode is sendmail use send mailer;
        if(in_array($mode, $modes) && $mode == "smtp"){
            $this->setHandler( new SmtpMailer([
                'host' => $this->config->get("server.mail.outgoing-server"),
                'username' => $this->config->get("server.mail.outgoing-server-username"),
                'password' => $this->config->get("server.mail.outgoing-server-password"),
                'secure' => $this->config->get("server.mail.outgoing-server-security", ""),
                'port'=> $this->config->get("server.mail.outgoing-server-port", 25),
            ]));
        }else{
            $this->setHandler( new SendmailMailer() );
        }
    }

    protected function setHandler(Handler $handler){
        $this->handler = $handler;
    }

    /**
     * Returns the raw message object. Use wisely
     *
     * @return mixed
     */
    public function getComposedMessage(){
        return $this->message;
    }

    public function compose($message, $to, $html = false ){


        //Check that we are sending to a valid email address;
        if(!$this->application->validate->isEmail($to)){
            throw new InvalidException("Mailer: {$to} is an invalid email address");
            return;
        }

        if(!$this->application->validate->isString($message)){
            throw new InvalidException("Mailer: Message to be sent must be a string");
            return;
        }

        $this->message = new Message;
        $this->message->addTo( $to );
        $this->message->setFrom(
            $this->config->get("server.mail.outgoing-address","outmail@budkit.org"),
            $this->config->get("setup.site.name","Budkit")
        );

        $this->addMessage( $message, $html);


        return $this;

    }

    public function setSubject( $subject ){
        $this->message->setSubject( $subject );

        return $this;
    }

    public function setFrom( $email, $name = null ){
        $this->message->setFrom($email, $name);

        return $this;
    }

    public function addTo( $to ){
        $this->message->addTo( $to );

        return $this;
    }


    public function addMessage ( $message , $html = false ){
        //Set the body
        if($html){
            $this->message->setHtmlBody( $message );
        }else{
            $this->message->setBody( $message );
        }

        return $this;
    }

    public function send(){

        if(!$this->message) return false; //throw warning must create a new message

        //Clean up;
        $this->handler->send( $this->message );
        $this->message = new Message; //or null?

        return true;
    }

}