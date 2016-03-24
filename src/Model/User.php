<?php
/**
 * Created by PhpStorm.
 * User: livingstonefultang
 * Date: 14/09/15
 * Time: 22:50
 */

namespace Budkit\Cms\Model;

use Budkit\Authentication\User as AuthenticatedUser;

class User extends AuthenticatedUser
{


    public function isMemberOfAuthorityGroup($groupId, $inheritance = true)
    {

        //get this userAuthority;
        //What about the public?
        $publicAuthority = $this->config->get("setup.site.public-authority", NULL);

        //Will always be a member of the public
        if ($groupId == $publicAuthority) return true;

        $authorities = $this->getUserAuthorities();
        $authorityGroup = null;

        foreach ($authorities as $group) {
            //1.The easiest thing to do is check if we have the authority group defined
            if ($group['authority_id'] == $groupId) {
                return true;
            }
            //checking for inheritance
            if ($inheritance) {
                if (is_null($authorityGroup)) {
                    $authority = $this->application->createInstance(Authority::class);
                    $authorityGroup = $authority->load($groupId);
                }
                if (is_array($authorityGroup)) {
                    // compare lft and rgt to find children
                    if ($group['lft'] > $authorityGroup['lft'] && $group['rgt'] < $authorityGroup['rgt']) {
                        return true;
                    }
                }
            }
        }

        return false;

    }


    public function getUserAuthorities()
    {

        $authoritiesSQLc = "SELECT o.authority_id, a.lft, a.rgt, a.authority_name,a.authority_parent_id FROM ?objects_authority AS o LEFT JOIN ?authority AS a ON o.authority_id=a.authority_id WHERE o.object_id = {$this->database->quote((int)$this->getCurrentUser()->getObjectId())} ORDER BY a.lft ASC";
        $authoritiesSQL = $this->database->prepare($authoritiesSQLc);

        return $authoritiesSQL->execute()->fetchAll();

    }
}