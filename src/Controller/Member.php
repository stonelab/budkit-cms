<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Menu;
use Budkit\Cms\Model\User;
use Budkit\Cms\Helper\Controller;
use Budkit\Dependency\Container as Application;
use Budkit\Cms\Model\Story;
use Budkit\Authentication\Authenticate;
use Budkit\Authentication\Type\DbAuth;
use Budkit\Authentication\Type\Ldap;
use Budkit\Authentication\Type\Openid;
use Budkit\Event\Event;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Whoops\Example\Exception;

class Member extends Controller {

    protected $user;


    public function __construct(Application $application, User $user, Menu $menu) {

        parent::__construct($application, $menu);

       // $this->view->appendLayoutSearchPath( Provider::getPackageDir()."layouts/");
        $this->user = $user;
    }

    public function view($id, $format = "html"){

        echo "viewing profile with Id". $id;

        return $this->index();
    }


    public function index($username, $format = 'html') {

            //echo "Browsing in {$username} format {$format}";

            $user = empty($username)? $this->user->getCurrentUser() : $this->user->loadObjectByURI( $username );

            // echo "Pages admin";
            $this->view->setData("title", t("@{$user->getObjectURI()}"));
            
            if($format == "html") $this->view->setData("sbstate", "minimized");

            $this->view->setData("user", $user->getPropertyData());

            $story = $this->application->createInstance( Story::class );
            $graph = $story->getBySubject( $user->getPropertyValue("user_name_id") );

            $this->view->setData("stories", getArrayObjectAsArray(  $graph->getEdgeSet()  ) );

            //$this->view->addToBlock("main", 'import://member/member-profile');
            $this->view->setLayout("member/member-profile");

    }

    final public function resetPassword(){

        $this->view->setData("title", "Reset Password");
        $this->view->setLayout("member/reset");
        

    }


    final public function resendVerificationEmail( $verification = null, User $user ){


        if(!$verification){

            $verification = getRandomString(30, false, true) ;
            $user->setPropertyValue("user_verification", $verification );

            if (!$user->saveObject( $user->getPropertyValue("user_name_id"), "user", null, false)) {
                //There is a problem!
                return false;
            }
        }

        $salt   = base64_encode($user->getPropertyValue("user_name_id"));
        $mail   = array(
            "subject"=>"Welcome to ".$this->application->config->get("setup.site.name", "Budkit"),
            "link"=> $this->application->uri->externalize("/member/signin/verify/{$verification}:{$salt}")
        );

        //Sending an email;
        try{

            $default  = "Hi {$user->getPropertyValue("user_first_name")}. Verify your email with this link {$mail['link']}" ;
            $renderer = $this->view;
            $renderer->setData("email-body", $this->application->config->get("email.verification.text" , $default ));
            $message = $renderer->render("email", true); //partial must be set to true

            $this->application->mailer
                ->compose($message, $user->getPropertyValue("user_email"), [
                    'recipient'=> $user->getPropertyValue("user_first_name"),
                    'link'=>$mail['link'] ], true )
                ->setSubject( $this->application->config->get("email.verification.subject" , $mail['subject'] )  )
                ->send();

            $this->response->addAlert(t("Before you can login, please check your inbox ({$user->getPropertyValue("user_email")}), and click on a special link we've just sent you to verify your email."), "info");

        }catch (\Exception $e){

            $this->response->addAlert(t("We were unable to send out a verification email."), "error");
            //$this->application->dispatcher->returnToReferrer();
        }

    }


    final public function signout() {

        $session = $this->user->getSession();
        $session->destroy();

        //echo $return;
        //Send back to homepage
        //$this->alert(_t("You have been logged out"), "", "info");
        //$this->redirect("/");
        $this->application->dispatcher->redirect("/member/signin");
    }

    public function verifyEmail( $key ){


        $parts = explode(":", $key);
        $verification = reset($parts);
        $usernameid   = base64_decode( end($parts) );


        $user  = $this->user->loadObjectByURI( $usernameid  );

        if($user->getPropertyValue("user_verification") == $verification ) {

            $user->setPropertyValue("user_verified", "verified");
            $user->setPropertyValue("user_verification",  getRandomString(30, false, true));
            $user->defineValueGroup("user");

            if (!$user->saveObject($user->getPropertyValue("user_name_id"), "user", $user->getObjectId(), false)) {
                //There is a problem!

                //die;

                return false;
            }

            $this->response->addAlert(t("Congratulations {$user->getPropertyValue("user_first_name")}, your account has now been verified. Please log in again"), "success");
        }else{

            $this->response->addAlert(t("We were unable to verify an account with the code/link provided. Try again"), "error");
        }


        $this->application->dispatcher->redirect("/member/signin", HTTP_FOUND, null);

    }

    public function signin(){

        //Oauth?
        //1. load Authenticate\oAuth, Get Request Token
        //2. Redirect to Provider Authorize. On Authorize POST back to controller/login with
        //3.
        //@TODO should we allow get authentication? /usernameid:xyz/usernamepass:norman/auth_handler:dbauth/ etc?
        //if ($this->input->methodIs("post")){


        if ($this->application->input->methodIs("post")) {

            //1. Check that we have a valid username and password
            $credentials = array();

            $handlers = array(
                "dbauth"=> DbAuth::class,
                "ldap"=> Ldap::class,
                "openID"=> Openid::class,
            );

            //authentication;
            $authhandler = $this->application->input->getString('handler', NULL, 'post'); //must be defined
            $authhandler = (empty($authhandler)) ? "dbauth" : $authhandler;

            if (!empty($authhandler) && array_key_exists($authhandler, $handlers)):

                $credentials['usernameid'] = $this->application->input->getString('user_name_id', '', 'post'); //usernameid will only be obtained from POST data
                $credentials['usernamepass'] = $this->application->input->getString('user_password', '', 'post'); //unsernamepassword will only be obtained from POST data

                $authenticate = $this->application->createInstance(Authenticate::class);

                try{
                    $pass = $authenticate->execute(
                        $credentials, $this->user, $this->application->createInstance( $handlers[$authhandler] , [$this->user, $this->application->encrypt, $this->application->validate] )
                    );
                    if($pass){

                        //print_R($pass); die;

                        $currentUser    = $this->user->getCurrentUser();
                        $currentUserIp  = $this->application->input->getVar('REMOTE_ADDR', \IS\STRING, '', 'server');

                        $verified       = $this->user->getPropertyValue("user_verified");

                        //User verification;
                        if(empty($verified) ) {

                            $session = $this->user->getSession();

                            $session->unlock("auth");
                            $session->remove("handler", "auth");

                            $session->update($session->getId());

                            $this->resendVerificationEmail(null, $this->user);

                            $this->application->dispatcher->returnToReferrer();

                            return false;
                        }

                        $this->response->addAlert(t("Welcome back {$currentUser->getPropertyValue('user_first_name')}"), "info");

                        //Record a login event;
                        $this->application->log->tick("login", ["ip"=>$currentUserIp,"user"=>$currentUser->getObjectId() ]);

                        //Redirect to dashboard or to last url?
                        $session        = $this->application->session;
                        $interceptedURL = $session->get("interceptedPath");
                        $redirectTo     = !empty($interceptedURL) && $this->permission->isAllowed( $interceptedURL, null, "view") ? $interceptedURL :  "/member/timeline";

                        //remove the interceptedPath var.
                        $session->remove("interceptedPath");

                        $this->application->dispatcher->redirect($redirectTo, HTTP_FOUND, null);

                        //return;
                    }else{
                        //Tell the user why the authentication failed
                        $this->response->addAlert(t('User authentication has failed with the credentials you provided. Try again'), "error");
                        //return false;
                    }
                } catch (Exception $exception) {

                    //@TODO do something with the exception;
                    //May be display a message about the failed auth
                    //log the exception;

                    //set a message saying something very bad happened;
                    $this->response->addAlert(t('User authentication has failed with the credentials you provided. Try again'), "error");
                    //return false;
                }
            endif;
        }

        $this->view->setData("title", "Please Sign In");
//        $this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/signin");

    }

    public function signup(){

        $this->view->setData("title", "Create an Account");
//        $this->view->setData("name", "Livingstone");
        $this->view->setLayout("member/signup");

        if($this->application->input->methodIs("post")){

            if(!$this->user->getCurrentUser()->isAuthenticated()) {

                //2. Prevalidate passwords and other stuff;
                $username = $this->application->input->getString("user_first_name", "", "post", FALSE, array());
                $usernameid = $this->application->input->getString("user_name_id", "", "post", FALSE, array());
                $userpass = $this->application->input->getString("user_password", "", "post", FALSE, array());
                $userpass2 = $this->application->input->getString("user_password_2", "", "post", FALSE, array());
                $useremail = $this->application->input->getString("user_email", "", "post", FALSE, array());
                //3. Encrypt validated password if new users!
                //4. If not new user, check user has update permission on this user
                //5. MailOut

                if (empty($userpass) || empty($username) || empty($usernameid) || empty($useremail)) {
                    //Display a message telling them what can't be empty
                    $this->response->addAlert(t('Please provide us with at least your first name, a unique alphanumeric username, e-mail address and password'), "error");
                    return false;
                }

                //3. Encrypt validated password if new users!
                //4. If not new user, check user has update permission on this user
                //5. MailOut

                if (empty($userpass) || empty($username) || empty($usernameid) || empty($useremail)) {
                    //Display a message telling them what can't be empty
                    $this->response->addAlert(t('Please provide at least a Name, Username, E-mail and Password'), "error");
                    return false;
                }

                //Validate the passwords
                if ($userpass <> $userpass2) {
                    $this->response->addAlert(t('The user passwords do not match'), "error");
                    return false;
                }

                //6. Store the user
                if (!$this->user->store($this->application->input->data("post"), true)):
                    $this->application->addAlert("We could not create your account", "error");
                    return false;
                endif;

                //Account successfully created. Redirect to sign in page;
                $this->response->addAlert(t('You account has been successfully created.'), "info");

                //@TODO attach post user sign up event;


                //@TODO if email verification is required;
                $this->user = $this->user->loadObjectByURI( $usernameid, [], true);

                $onSignUp = new Event('Member.onSignUp', $this, $this->user);
                $this->observer->trigger( $onSignUp ); //Parse the Node;

                //Redirect to the sign up page;
                $this->application->dispatcher->redirect("/member/signin", HTTP_FOUND, null);

            }else{
                //You need to be logged out!
                $this->response->addAlert(t('You are already logged in and cannot create another account.'), "error");
            }

        }

    }


    public function read($id, $format = 'html') {
        echo "Reading {$id} in {$format} format";
    }

    public function edit($id = 'new', $format = 'html') {
        echo "Editing {$id} in {$format} format";
    }

    public function add() {
        echo "Adding...";
    }

    public function delete() {
        echo "Delete...";
    }

    public function create() {
        echo "Creating...";
    }

    public function update() {
        echo "Updating...";
    }

    public function replace() {
        echo "Replacing...";
    }

    public function options() {
        echo "Options...";
    }
}