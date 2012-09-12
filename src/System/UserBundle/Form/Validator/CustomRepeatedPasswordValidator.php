<?php

namespace System\UserBundle\Form\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;

class CustomRepeatedPasswordValidator extends ConstraintValidator
{
    public function validate(FormInterface $form)
    {
        if($form['plainPassword']->getData() != $form['plainPasswordConfirm']->getData())
        	$form['plainPassword']->addError(new FormError('The passwords you entered did not match'));
    }
}

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsAlphanumericValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/^[a-zA-Za0-9]+$/', $value, $matches)) {
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }
    }
}

class ProtocolClassValidator extends ConstraintValidator
{
    public function validate($protocol, Constraint $constraint)
    {
        if ($protocol->getFoo() != $protocol->getBar()) {
            $this->context->addViolationAtSubPath('foo', $constraint->message, array(), null);
        }
    }
}