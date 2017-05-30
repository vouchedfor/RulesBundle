<?php

namespace VouchedFor\RulesBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Json extends Constraint
{
    public $message = 'This value is not a valid Json array.';
    public $notJsonArrayErrorMessage = 'The value IS valid Json, but is not an array.';
    public $emptyJsonArrayErrorMessage = 'The value IS a valid Json array, but is empty.';
}
