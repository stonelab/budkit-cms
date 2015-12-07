<?php

namespace Budkit\Cms\Controller;

use Budkit\Cms\Helper\Menu;
use Budkit\Cms\Model\User;
use Budkit\Cms\Helper\Controller;
use Budkit\Dependency\Container as Application;

use Budkit\Authentication\Authenticate;
use Budkit\Authentication\Type\DbAuth;
use Budkit\Authentication\Type\Ldap;
use Budkit\Authentication\Type\Openid;
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


    public function index($format = 'html') {

    }

    final public function resetPassword(){

        $this->view->setData("title", "Reset Password");
        $this->view->setLayout("member/reset");

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

                        $currentUser    = $this->user->getCurrentUser();
                        $currentUserIp  = $this->application->input->getVar('REMOTE_ADDR', \IS\STRING, '', 'server');

                        $this->response->addAlert(t("{$currentUser->getPropertyValue("user_first_name")}!!! Welcome back :)"), "info");

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
                    }
                } catch (Exception $exception) {

                    //@TODO do something with the exception;
                    //May be display a message about the failed auth
                    //set a message saying something very bad happened;
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

                //@TODO if email verification is required;
                $this->response->addAlert(t("Before you can login, please check your inbox ({$useremail}), and click on a special link we've sent you to verify your account."), "warning");


                //Redirect to the sign up page;
                $this->application->dispatcher->redirect("/member/signin", HTTP_FOUND, null, $this->response->getAlerts());
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