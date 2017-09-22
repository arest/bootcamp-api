<?php
// src/AppBundle/Security/Voter/RoutePermissionVoter.php
namespace AppBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use AppBundle\Entity\User;

class RoutePermissionVoter extends Voter
{
    const VIEW_ROUTE = 'view_route';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW_ROUTE,))) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $route, TokenInterface $token)
    {

        return true;
        
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // ROLE_SUPER_ADMIN can do anything! The power!
        if ($this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN'))) {
            return true;
        }

        switch ($attribute) {
            case self::VIEW_ROUTE:
                return $this->canView($route, $user);
            break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView($route, User $user)
    {
        return true;

        // foreach ($user->getPermissionGroups() as $group) {
        //     foreach ($group->getPermissions() as $permission) {
        //         if (in_array($route,$permission->getRoutes())) {
        //             return true;
        //         }
        //     }
        // }
        // return false;
    }
}