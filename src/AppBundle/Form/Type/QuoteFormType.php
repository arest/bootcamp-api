<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

		$builder

            ->add('author', null, array(
                'label' => 'admin.crud.form.author',
                'required' => true,
            ))

            ->add('content', null, array(
                'label' => 'admin.crud.form.content',
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
			'data_class' => 'AppBundle\Entity\Quote',
            'csrf_protection'   => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'quote';
    }
}