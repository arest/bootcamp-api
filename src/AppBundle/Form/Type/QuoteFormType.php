<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

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

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                if (isset($data['authorId'])) {
                    $data['author'] = $data['authorId'];
                    unset($data['authorId']);
                }
                $event->setData($data);
        });
    }

	/**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Quote',
            'csrf_protection'   => true,
            'allow_extra_fields' => true,
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