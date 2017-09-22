<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

		$builder

            // ->add('id', null, array(
            //     'label' => 'ID',
            //     'mapped' => false,
            // ))

            ->add('email', null, array(
                'label' => 'admin.crud.form.email',
                'required' => true,
            ))

            ->add('firstName', null, array(
                'label' => 'admin.crud.form.firstName',
                'required' => true,
            ))
            ->add('lastName', null, array(
                'label' => 'admin.crud.form.lastName',
                'required' => true,
            ))
        ;

    }

	/**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Author',
            'csrf_protection'   => true,
            'allow_extra_fields' => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'author';
    }
}