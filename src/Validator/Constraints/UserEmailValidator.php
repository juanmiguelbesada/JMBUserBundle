<?php

namespace JMB\UserBundle\Validator\Constraints;

use JMB\UserBundle\Manager\UserManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UserEmailValidator extends ConstraintValidator
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * UserEmailValidator constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UserEmail) {
            throw new UnexpectedTypeException($constraint, UserEmail::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (null === $this->userManager->findUserByEmail($value)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ email }}', $value)->addViolation();
        }
    }
}
