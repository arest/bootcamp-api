<?php

namespace AdminBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class CRUDVoter extends Voter
{
    // these strings are just invented: you can use anything
    const CREATE = 'create';
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const LISTING = 'list';
    const PREVIEW = 'preview';

    public function supports($attribute, $subject)
    {
        return in_array($attribute, array(
            self::CREATE,
            self::VIEW, 
            self::EDIT,
            self::DELETE,
            self::LISTING,
            self::PREVIEW,
        ));
    }


    protected function voteOnAttribute($attribute, $item, TokenInterface $token)
    {

        $user = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (true !== $this->getSupportedUser($user)) {
            // We grants access as soon as there is one voter granting access;
            return false; // 
        }

        switch($attribute) {
            case self::CREATE:
                return $this->canCreate($item, $user);
            case self::VIEW:
                return $this->canView($item, $user);
            case self::EDIT:
                return $this->canEdit($item, $user);
            case self::DELETE:
                return $this->canDelete($item, $user);
            case self::LISTING:
                return $this->canList($item, $user);
            case self::PREVIEW:
                return $this->canPreview($item, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    abstract protected function getSupportedUser(UserInterface $user);
    abstract protected function canCreate( $item, UserInterface $user);
    abstract protected function canView( $item, UserInterface $user);
    abstract protected function canEdit( $item, UserInterface $user);
    abstract protected function canDelete( $item, UserInterface $user);
    abstract protected function canList( $item, UserInterface $user);
    abstract protected function canPreview( $item, UserInterface $user);

}