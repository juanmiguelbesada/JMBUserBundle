<?php

namespace JMB\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserEmail extends Constraint
{
    public $message = 'The email "{{ email }}" does not exists.';
}
