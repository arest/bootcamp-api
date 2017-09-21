<?php
namespace AppBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Model\Authorize;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;
use OAuth2\OAuth2RedirectException;

class AuthorizeFormHandler
{
    protected $request;
    protected $form;
    protected $context;
    protected $oauth2;

    public function __construct(Request $request, TokenStorage $context, OAuth2 $oauth2)
    {
        $this->request = $request;
        $this->context = $context;
        $this->oauth2 = $oauth2;
    }

    public function process(Authorize $authorize, Form $form )
    {
        $this->form = $form;

        $this->form->setData($authorize);

        if ($this->request->getMethod() == 'POST') {
            
            $this->form->handleRequest($this->request);

            if ($this->form->isValid()) {
                
                try {
                    $user = $this->context->getToken()->getUser();
                    return $this->oauth2->finishClientAuthorization(true, $user, $this->request, null);
                } catch (OAuth2ServerException $e) {
                    return $e->getHttpResponse();
                }
                
            }
            
        }

        return false;
    }

}
