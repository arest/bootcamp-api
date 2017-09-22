<?php 
// src/AppBundle/Security/AccessTokenProvider.php
namespace AppBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class AccessTokenUserProvider implements UserProviderInterface
{

    private $repository;

    public function __construct( $repository ) 
    {
        $this->repository = $repository;
    }

    public function getUserForAccessToken($accessToken)
    {
    	$accessToken = str_replace('Bearer ','', $accessToken);
    	
        $token = $this->repository->findOneBy(['token' => $accessToken ]);
        if ( $token ) {
            return $token->getUser()->getUsername();
        }

        return null;
    }

    public function loadUserByUsername($username)
    {
        return new User(
            $username,
            null,
            // the roles for the user - you may choose to determine
            // these dynamically somehow based on the user
            array('ROLE_API')
        );
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
    	return true;
        return User::class === $class;
    }
}