<?php

namespace Budkit\Cms\Controller\Admin\Setup\Helpers;
use Budkit\Cms\Model\User;
use Budkit\Datastore\Database;
use Budkit\Datastore\Encrypt;
use Budkit\Dependency\Container;
use Budkit\Parameter\Manager as Config;
use Exception;

/**
 * Performs system installation
 *
 * @category  Application
 * @package   Data Model
 * @license   http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version   1.0.0
 * @since     Jan 14, 2012 4:54:37 PM
 * @author    Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 * 
 */
final class Install{

    protected $config;

    protected $encryptor;
    

    public function __construct(Config $config, Encrypt $encryptor){
        $this->config = $config;
        $this->encryptor = $encryptor;
    }
    /**
     * Registers a superadministrator at installation
     * @return boolean
     */
    public function superadmin(User $account, Container $application, Database $database){

        //@TODO create master user account
        //1. Load the model

        $config     = $this->config;
        //$database   = \Library\Database::getInstance();

        //2. Prevalidate passwords and other stuff;
        $username   = $application->input->getString("user_first_name",  "","post", FALSE, array());
        $usernameid = $application->input->getString("user_name_id", "","post",FALSE, array());
        $userpass   = $application->input->getString("user_password", "", "post", FALSE, array());
        $userpass2  = $application->input->getString("user_password_2", "", "post", FALSE, array());
        $useremail  = $application->input->getString("user_email", "", "post", FALSE, array());
        //3. Encrypt validated password if new users!
        //4. If not new user, check user has update permission on this user
        //5. MailOut
        
        if(empty($userpass)||empty($username)||empty($usernameid)||empty($useremail)){
            //Display a message telling them what can't be empty
            throw new Exception(t('Please provide at least a Name, Username, E-mail and Password') );
            return false;
        }
        
        //Validate the passwords
        if($userpass <> $userpass2){
            throw new Exception(t('The user passwords do not match') );
            return false;
        }
        
        //6. Store the user
        if(!$account->store( $application->input->data("post") , true)){
            //Display a message telling them what can't be empty
            throw new Exception( t('Could not store the admin user account')  );
            return false;
        }

        //Add this user to the superadministrators group!
        //$adminObject    = $account->getObjectByURI( $usernameid );
        $adminAuthority = $this->config->get( "setup.site.superadmin-authority", NULL);
        //Default Permission Group?
        if(!empty($adminAuthority)){
            $query = "INSERT INTO ?objects_authority( authority_id, object_id ) SELECT {$database->quote((int)$adminAuthority)}, object_id FROM ?objects WHERE object_uri={$database->quote($usernameid)}";
            $database->exec($query);
        }
        
        //@TODO Empty the setup/sessions folder
       // \Library\Folder::deleteContents( APPPATH."setup".DS."sessions" ); //No need to through an error
        
        //Completes installation

        //set session handler to database if database is connectable
        $config->set("setup.session.store", "database");
        $config->set("setup.database.installed", TRUE  );


        if(!$config->saveParams() ){

            throw new Exception("could not save config");

            return false;
        }
        return true;
    }
    
    /**
     * Executes the installation
     * 
     * @return boolean
     */
    public function database(Container $application){
             
        $config     = $this->config;

        //Stores all user information in the database;
        $dbName = $application->input->getString("dbname", "", "post");
        $dbPass = $application->input->getString("dbpassword", "", "post");
        $dbHost = $application->input->getString("dbhost", "", "post");
        $dbPref = $application->input->getString("dbtableprefix", "", "post");
        $dbUser = $application->input->getString("dbusername", "", "post");
        $dbDriver = $application->input->getString("dbdriver","MySQLi", "post");
        $dbPort = $application->input->getInt("dbport","", "post");
        
        if(empty($dbName)){
            throw new \Exception(t("Database Name is required to proceed."));
            return false;
        }
        if(empty($dbDriver)){
            throw new \Exception(t("Database Driver Type is required to proceed."));
            return false;
        }
        if(empty($dbUser)){
            throw new \Exception(t("Database username is required to proceed"));
            return false;
        }
        if(empty($dbHost)){
            throw new \Exception(t("Please provide a link to your database host. If using SQLite, provide a path to the SQLite database as host"));
            return false;
        }
        $config->set("setup.database.host", $dbHost );
        $config->set("setup.database.prefix", $dbPref );
        $config->set("setup.database.user", $dbUser );
        $config->set("setup.database.password", $dbPass );
        $config->set("setup.database.name", $dbName );
        $config->set("setup.database.driver", strtolower($dbDriver ) );
        $config->set("setup.database.port", intval($dbPort) );


        //Try connect to the database with these details?
        try{

            $application->createInstance("database",
                [
                    $application->config->get("setup.database.driver"), //get the database driver
                    $application->config->get("setup.database") //get all the database options and pass to the driver
                ]
            );

        } catch (Exception $exception) {


           //@TODO do something with this exception;

            return false;

        }


        //@TODO run the install.sql script on the connected database
         $schema = new Schema();


         //print_r($schema::$database);
        if(!$schema->createTables( $application->database )){

            echo "wtf";

            return false;
        }

        //generate encryption key
        $encryptor  = $this->encryptor;
        $encryptKey = $encryptor->generateKey( time().getRandomString(5) );

        $config->set("setup.encrypt.key", $encryptKey );

        if(!$config->saveParams() ){

            throw new Exception("could not save config");

            return false;
        }
        return true;
    }


}

