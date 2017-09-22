<?php

namespace AdminBundle\Form\Type\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PermissionFormType extends AbstractType
{
    protected $router;

    public function __construct( $router )
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
            ->add('name', TextType::class, array(
                'label' => 'admin.crud.form.name',
                'required' => true,
            ))
            ->add('routes', ChoiceType::class, array(
                'label' => 'admin.crud.form.routes',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->getRoutes(),
            ))
            ->add('groups', null, array(
                'label' => 'admin.crud.form.groups',
                //'class' => 'AdminBundle:PermissionGroup',
                'required' => false,
                //'multiple' => true,
                'expanded' => true,
            ))
        ;
    }

    protected function getRoutes() {
        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();
        $choices = [];

        foreach ($allRoutes as $route => $params) 
        {
            if ( false !== strpos($route, '_app_crud_') ) {
                $label = str_replace( ['_app_crud_','_'],['',' '], $route );
                $choices[$label] = $route;
            }
        }
        ksort($choices);
        return $choices;
    }

	/**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'AdminBundle\Entity\Permission',
            'translation_domain' => 'AdminBundle',
            'csrf_protection'   => false,
        ));
    }

    public function getBlockPrefix()
    {
        return 'simple_admin_permission';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}