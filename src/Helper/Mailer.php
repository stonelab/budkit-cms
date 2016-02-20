<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 28/01/2016
 * Time: 06:26
 */

namespace Budkit\Cms\Helper;

use Budkit\Dependency\Container;
use Budkit\Event\Event;
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

    public function compose($message, $to, $data = [], $html = false ){

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

        $this->addMessage( $message, $data, $html);


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


    public function addMessage ( $message , $data = [], $html = false ){


        //die;
        //Search for (?<=\$\{)([a-zA-Z]+)(?=\}) and replace with data
        if (preg_match_all('/(?<=\@\{)([a-zA-Z]+)(?=\})/i', $message, $matches)) {

            $placemarkers = (is_array($matches) && isset($matches[0])) ? $matches[0] : [];
            $searches = [];
            $replaces = [];

            foreach ($placemarkers as $placemarker):

                $replace = $this->getData($placemarker, $data);

                if (is_string($replace)) {
                    $searches[] = '@{' . $placemarker . '}';
                    $replaces[] = $replace;
                }

            endforeach;

            $message = str_ireplace($searches, $replaces, $message);

        }

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


    protected function getData($path, $data)
    {

        //when the path is just $, the element is request the data array or string as is.
        //modifies such as ${config://} to get config data or do anything else fancy
        if (preg_match('|^(.*?)://(.+)$|', $path, $matches)) {

            $parseDataScheme = new Event('Layout.onCompile.scheme.data', $this, ["scheme" => $matches[1], "path" => $matches[2], "data"=>$data]);
            $parseDataScheme->setResult(null); //set initial result

            $observer = $this->application->observer;
            $observer->trigger($parseDataScheme); //Parse the Node;

            return $parseDataScheme->getResult();
        }

        $array = $data;
        $keys = $this->explode($path);


        //From this point we can only work with data arrays;
        if( is_array($array) || $array instanceof \ArrayAcces ) {

            foreach ($keys as $key) {
                if (isset($array[$key])) {
                    if($array instanceof \ArrayAcces){
                        $array = $array->offsetGet( $key );
                        //print_R($array);
                    }else {
                        $array = $array[$key];
                    }
                } else {
                    return "";
                }
            }

            return $array;
        }

        return null;
    }

    protected function explode($path)
    {
        return preg_split('/[:\.]/', $path);
    }

}