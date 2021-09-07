<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * authority.php
 *
 * Requires PHP version 5.3
 *
 * LICENSE: This source file is subject to version 3.01 of the GNU/GPL License 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl.txt  If you did not receive a copy of
 * the GPL License and are unable to obtain it through the web, please
 * send a note to support@stonyhillshq.com so we can mail you a copy immediately.
 *
 * @category   Utility
 * @author     Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 * @copyright  1997-2012 Stonyhills HQ
 * @license    http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version    Release: 1.0.0
 * @link       http://stonyhillshq/documents/index/carbon4/libraries/authorize/type/age
 * @since      Class available since Release 1.0.0 Jan 14, 2012 4:54:37 PM
 * 
 */

namespace Budkit\Cms\Helper\Authorize\Type;

use Budkit\Cms\Helper\Authorize\Permission;

/**
 * What is the purpose of this class, in one sentence?
 *
 * How does this class achieve the desired purpose?
 *
 * @category   Utility
 * @author     Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 * @copyright  1997-2012 Stonyhills HQ
 * @license    http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version    Release: 1.0.0
 * @link       http://stonyhillshq/documents/index/carbon4/libraries/authorize/type/age
 * @since      Class available since Release 1.0.0 Jan 14, 2012 4:54:37 PM
 */
final class Authority extends Permission  {

    /**
     * The user ID, whose authority and permission is being evaluated
     * @var string $userid
     */
    public $userid;

    /**
     * The currently authenticated users authority
     * @var string contstant
     */
    public $impliedAuthority = AUTHROITY_IMPLIED_ANONYMOUS;

    /**
     * The directly awarded authority permission of current user
     * @var array
     */
    public $permissions = array();

    /**
     * The implied authorities/permissions
     * @var areas
     */
    public $areas = array();



    protected $database;


    public function __construct(Database $database){

        $this->database = $database;

    }

    /**
     * Sets the current user's authority
     *
     * @param type $implied
     * @property-write string $impliedAuthority
     * @return void
     */
    final public function setAuthority($implied) {

        $this->impliedAuthority = $implied;
    }

    /**
     * Determines the current user (in session) 's authority or that of a specified user id
     *
     * @param string $userid
     * @property-read string $impliedAuthority
     * @return string The implied Authority of the user
     */
    final public function getAuthority($userid) {

    }

    /**
     * Returns the authority tree
     *
     * @uses Library\Datbase To get the user authority tree
     * @return Array;
     */
    final public function getAuthorityTree() {

        $database = $this->database;

        $statement = $database->select()->from('?authority')->between("lft", '1', '6')->prepare();
        $results = $statement->execute();

        $right = array();
    }

    /**
     * Gets the permissions givent to the authenticated users
     *
     * @param object $authenticated
     * @uses \Library\Authorize\Permission to determin execute permissions
     * @return object Permission
     */
    final public function getPermissions( $authenticated ) {

        //$authority      = $this;
        $this->userid = (int) $authenticated->get("user_id");

        //Authenticated?
        if ($authenticated->authenticated && !empty($this->userid)) {
            //At least we know the user is authenticated
            $this->setAuthority( AUTHROITY_IMPLIED_AUTHENTICATED );
        }
    }

    /**
     * Sets the object permission
     *
     * @param type $object
     * @param type $permission
     */
    final public function setPermission( $object, $permission = 777 ) {

    }

    /**
     *
     * Checks that the modifier (user/or even machine) defined (definition)
     * has the right authority (group) and that the authority (group) has the
     * right permission (read/write/execute) defined (in definition) to the
     * interact with the action
     *
     * DEFINITIONS
     *
     * Authority can "perform task"
     *
     * @param string $permission
     * @param string $object URI reference
     * @return void
     */
    final public function can($permission="access", $object) {

    }


}

