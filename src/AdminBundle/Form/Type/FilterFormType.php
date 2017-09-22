<?php

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('search', TextType::class, array(
                'label' => 'admin.filter.search.label',
                'required' => false,
                'attr' => array(
                	'placeholder' => 'admin.filter.search.label'
                ),
            ))
        ;
    }

	/**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'AdminBundle\Model\Filters',
            'translation_domain' => 'AdminBundle',
            'csrf_protection'   => false,
        ));
    }

    public function getBlockPrefix()
    {
        return 'filters';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}