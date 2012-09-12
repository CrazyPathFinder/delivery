<?php

namespace System\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class EditUserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email',
            	array(
            		'label' => 'Email'
            	)
            )
            ->add('emailOld', 'hidden',
                array('property_path' => false)
            )
        	->add('newPassword', 'repeated', 
                array(
                    'type' => 'password',
                    'property_path' => false,
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'form.password'),
                    'second_options' => array('label' => 'form.password_confirmation')
                )
            )
            ->add('oldPassword', 'password',
                array(
                    'property_path' => false,
                    'label' => 'Old password'
                )
            )
        ;   
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'System\UserBundle\Entity\User',
            'cascade_validation' => true,
            'validation_groups' => array('edit_profile')
        ));
    }

    public function getName()
    {
        return 'edit_user_type';
    }

}
