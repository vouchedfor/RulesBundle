<?php

namespace VouchedFor\RulesBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates whether a value is valid Json.
 */
class JsonValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $jsonArray = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
            return;
        }

        if (!is_array($jsonArray)) {
            $this->context->buildViolation($constraint->notJsonArrayErrorMessage)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
            return;
        }

        if (!count($jsonArray)) {
            $this->context->buildViolation($constraint->emptyJsonArrayErrorMessage)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
            return;
        }
    }
}
