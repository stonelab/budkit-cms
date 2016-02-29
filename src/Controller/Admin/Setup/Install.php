<?php

namespace Budkit\Cms\Controller\Admin\Setup;

use Budkit\Cms\Model\User;
use Budkit\Cms\Provider;
use Budkit\Cms\Controller\Admin;
use Budkit\Cms\Controller\Admin\Setup\Helpers;
use Budkit\Dependency\Container as Application;
use Budkit\Routing\Controller;

class Install extends Controller {

    //This controller can't use the helper controller constructor!
    public function __construct(Application $application) {

        parent::__construct($application);

        $this->view->appendLayoutSearchPath( Provider::getPackageDir()."layouts/");
    }


    public function index($format = 'html') {
        //echo "Browsing in {$format} format";

        $this->view->setData("name", "Livingstone");

        $step = $this->application->input->getInt("step", 1);

        switch ($step) {

            case 2:
                $this->step2();
                break;
            case 3:
                $this->step3();
                break;
            case 4:
                $this->step4();
                break;
            case 5:
                $this->step5();
                break;
            case 1:
            default:
                $this->step1();
                break;
        }

        $this->view->setLayout("admin/setup/install");
    }


    /**
     * Step1 of the installation process. Displays the end user license
     * @return void
     */
    protected function step1(){

        //this is step 1;
        $this->view->addToBlock("form", "import://admin/setup/license");
        $this->view->setData("step", "1");
        $this->view->setData("title", t("Installation | EULA"));

        return;

    }

    /**
     * Step2 of the installation process. Validates that the EULA has been
     * accepted from step 1. Performs a validation of the system requirements.
     * @todo This method does not at this stage stop the installation proces on any failures
     * @return void
     */
    protected function step2(){

        $this->view->addToBlock("form", "import://admin/setup/requirements");
        $this->view->setData("step", "2");
        $this->view->setData("title", t("Installation | Requirements"));

        $systemcheck = new Helpers\Requirements();
        $requirements = [];
        $directives = require_once( PATH_CONFIG.'/requirements.inc' );


        //Check Modules
        $server = ["title"=>"Required Server Software", "tests"=>[]];
        foreach( $directives["server"] as $name=>$directive ){
            $server["tests"][] = $systemcheck->testServerVersions($name, $directive);
        }
        $requirements[] = $server;


        //Check Modules
        $modules = ["title"=>"Required Modules", "tests"=>[]];
        foreach( $directives["modules"] as $name=>$directive ){
            $modules["tests"][] = $systemcheck->testModule($name, $directive);
        }
        $requirements[] = $modules;

        //Check Modules
        $limits = ["title"=>"Required Resource Limits", "tests"=>[]];
        foreach( $directives["limits"] as $name=>$directive ){
            $limits["tests"][] = $systemcheck->testLimit($name, $directive);
        }
        $requirements[] = $limits;


        //Check Modules
        $directories = ["title"=>"Required Folder Permissions", "tests"=>[]];
        foreach( $directives["directories"] as $name=>$directive ){

            $directories["tests"][] = $systemcheck->testFolderPermissions($directive["path"], $directive);
        }
        $requirements[] =  $directories;




        $this->view->setDataArray( ["requirements"=> $requirements ]);


        return;

    }

    /**
     * Step3 of the installation process. Displays the database configuration form
     * @return void
     */
    protected function step3(){

        $this->view->addToBlock("form", "import://admin/setup/database");
        $this->view->setData("step", "3");
        $this->view->setData("randomstring", strtolower( getRandomString('5')."_" ) ); //may be case sensitive on some systems
        $this->view->setData("title", t("Installation | Database Settings"));



        return;

    }

    /**
     * Step4. Performs the database table setup. Please note that this method does not
     * actually create or overwrite the database and as such the database must already exists.
     * If Database setup is successful, will display the master user setup form
     *
     * @return void
     */
    protected function step4(){


        //this is step 1;
        $this->view->addToBlock("form", "import://admin/setup/user");
        $this->view->setData("step", "4");
        $this->view->setData("title", t("Installation | Install SuperUser"));


        if ($this->application->input->methodIs("post")) {

            // $this->view->setData("alerts", [ ["message"=>t('Success. The database was successfully configured'),"type"=>'success'] ] );
            $install = new Helpers\Install($this->application->config, $this->application->encrypt );

            //preform the installation;
            if(!$install->database( $this->application )){
                $this->application->dispatcher->redirect("/admin/setup/install/3");
            }

            $this->response->addAlert("Wohooo! The database was successfully configure. Now please create a super user.", "info");


        }

        return;

    }

    /**
     * Step5. Registers an account for the master user/superadministrator. IF
     * Successfull will display a summary of the completed install process
     *
     * @return void
     */
    protected function step5(){

        $this->view->setData("step", "5");
        $this->view->setData("title", t("Installation | Install SuperUser"));


        if ($this->application->input->methodIs("post")) {


            //Because the database is still not fully,
            //we need to create it as follows

            $database = $this->application->createInstance("database",
                [
                    $this->application->config->get("setup.database.driver"), //get the database driver
                    $this->application->config->get("setup.database") //get all the database options and pass to the driver
                ]
            );

            //can't go back to 3 because we need database;
            $install = new Helpers\Install($this->application->config, $this->application->encrypt );
            $user    = $this->application->createInstance( User::class );

            //preform the installation;
            if(!$install->superadmin( $user , $this->application, $database )){

                // $this->view->setData("alerts", [ ["message"=>t('Success. The database was successfully configured'),"type"=>'success'] ] );
                $this->application->dispatcher->redirect("/admin/setup/install/4");

            }else{
                $this->application->dispatcher->redirect("/member/signin");
            }

        }

        //installation is complete
        $this->application->dispatcher->redirect("/admin/setup/install/4");

//        //Check we have all the information we need!
//        if(!$install->superadmin()){
//            $this->alert($install->getError(),_t('Something went wrong'),'error');
//            $this->set("step", "4");
//            $view->index() ;
//            return $this->output->setPageTitle(_t("Installation | Final Things"));
//        }
//        $this->alert(_t("Fantastico. All systems ready to go. Please make a note of the information below. If possible print this screen and keep it for your records") ,"","success");
//
//        //Return the install report as launch
//        $this->output->setPageTitle(_t("Installation Complete"));
//
//        return $view->readme();




        return;
    }
}