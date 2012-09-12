<?php
namespace System\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use System\UserBundle\Form\RegistrationUserType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('user', 'edit_user_type')
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
        return 'system_userbundle_editprofiletype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
        $resolver->setDefaults(array(
            'validation_groups' => array('edit_profile'),
            'cascade_validation' => true
        ));
    }
}
