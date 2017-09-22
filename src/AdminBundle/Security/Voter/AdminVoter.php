<?php

namespace AdminBundle\Security\Voter;

use Symfony\Component\Security\Core\User\UserInterface;

class AdminVoter extends CRUDVoter
{
    protected function getSupportedUser(UserInterface $user)
    {
        return true;
        
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }
    }

    protected function canCreate($item, UserInterface $user) 
    {
        return true;
    }

    protected function canView($item, UserInterface $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($item, $user)) {
            return true;
        }
    }

    protected function canEdit($item, UserInterface $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return true;
    }

    protected function canDelete($item, UserInterface $user)
    {
        return true;
    }

    protected function canList($item, UserInterface $user)
    {
        // if (get_class($item) == 'AppBundle\Entity\Page') {
        //     return false;
        // }
        return true;
    }

    protected function canPreview($item, UserInterface $user)
    {
        return true;
    }
}
