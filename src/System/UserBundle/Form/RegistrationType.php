<?php
namespace System\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use System\UserBundle\Form\RegistrationUserType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('user', 'registration_user_type')
            ->add('userName', null,
            	array(
            		'label' => 'Enter you name',
            		'required' => true
            	)
            )
        ;
    }

    public function getName()
    {
        return 'system_userbundle_registrationtype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
        $resolver->setDefaults(array(
            'validation_groups' => array('registration'),
            'cascade_validation' => true
        ));
    }
}
