<?php

namespace AdminBundle\Form\Type\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PermissionGroupFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'admin.crud.form.name',
                'required' => true,
            ))
            ->add('permissions', null, array(
                //'class' => 'AdminBundle:Permission',
                'label' => 'admin.crud.form.permissions',
                'required' => false,
                //'multiple' => true,
                'expanded' => true,
            ))
        ;
    }

	/**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'AdminBundle\Entity\PermissionGroup',
            'translation_domain' => 'AdminBundle',
            'csrf_protection'   => false,
        ));
    }

    public function getBlockPrefix()
    {
        return 'simple_admin_permission_group';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}