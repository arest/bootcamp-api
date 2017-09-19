<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Symfony\Component\Form\FormInterface;
use AppBundle\Model\Pagination;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class ApiController extends FOSRestController
{
    protected function list( $result )
    {

        $groups = array('list');

        $view = $this->view($result, 200)
                ->setHeader('X-Total-Count', count($result) )
                ->setHeader('Content-Range', count($result) )
        ;
        $context = $view->getContext();
        $context->setSerializeNull(true);
        $context->setGroups($groups);
        $view->setContext($context);

        return $this->handleView($view);
    }

    protected function getUser() 
    {
        // return $this->get('fos_user.user_manager')->findUserByUsernameOrEmail( 'andrea_restello@yahoo.it' );
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $user->getIsMaster() ? $user : $user->getMaster();
    }
    
    protected function exposeForm($form, $code = 400)
    {   
        $errors = $this->getErrorMessages($form);
        $message = "";
        foreach ($errors as $key => $error) {
            $message .= is_array($error) && isset($error[0]) && is_string ($error[0]) ? $error[0]."\n" : $key.' non valido'."\n";
        }
        return array( 'code' => $code, 'errors' => $errors, 'message' => $message );
    }

    protected function getErrorMessages($form, $father = null) 
    {
        $errors = array();
        foreach ($form->getErrors(true) as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }
            //$child->getName()
            $this->errors[] = array( 
                'field' => $father.'.'.$form->getName(),
                'code' => $template,
                'msg' => $this->container->get('translator')->trans($template, array(),'validators'),
            );
        }
        if ($form->count()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $name = $form->getName();
                    if ($father) {
                        $name = $father.'.'.$form->getName();
                    }
                    $this->getErrorMessages($child, $name );
                }
            }
        }
        return $errors;
    }

    protected function handleForm($form)
    {
        $this->errors = array();
        $this->getErrorMessages($form);
        $msg = array();
        foreach ($this->errors as $error) {
            $msg[] = $error['msg'];
        }
        return array(
            'error' => $this->errors,
            'msg' => join("\n",$msg),
        );
    }

    protected function formatError($field,$label)
    {
        $translator = $this->container->get('translator');
        return array(
            'error' => array( array(
                'field' =>  $field,
                'code' => $label,
                'msg' => $translator->trans($label,array(),'validators'), #"La password è scaduta. Preghiamo di resettarla"
            )),
            'msg' => $translator->trans($label,array(),'validators'),
        );
    }
}

