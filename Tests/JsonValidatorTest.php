<?php

namespace Symfony\Component\Validator\Tests\Constraints;

use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use VouchedFor\RulesBundle\Validator\Constraints\Json;
use VouchedFor\RulesBundle\Validator\Constraints\JsonValidator;

class JsonValidatorTest extends ConstraintValidatorTestCase
{
    protected function setUp(): void
    {
        $this->group = 'MyGroup';
        $this->metadata = null;
        $this->object = null;
        $this->value = 'InvalidValue';
        $this->root = 'root';
        $this->propertyPath = 'property.path';

        // Initialize the context with some constraint so that we can
        // successfully build a violation.
        $this->constraint = new NotNull();

        $this->context = $this->createContext();
        $this->validator = $this->createValidator();
        $this->validator->initialize($this->context);
    }

    protected function createValidator()
    {
        return new JsonValidator();
    }

    public function testNullIsInvalid()
    {
        $value = null;

        $this->validator->validate($value, new Json());

        $this->buildViolation('This value is not a valid Json array.')
            ->setParameter('{{ string }}', (string) $value)
            ->assertRaised();
    }

    /**
     * @dataProvider getValidJson
     */
    public function testValidCountries($json)
    {
        $this->validator->validate($json, new Json());

        $this->assertNoViolation();
    }

    public function getValidJson()
    {
        return [
            ['["abc","def"]'],
            ['["abc"]'],
            ['["abc|123","def|43"]'],
        ];
    }

    /**
     * @dataProvider getInvalidJson
     */
    public function testInvalidCountries($json, $message)
    {
        $constraint = new Json();

        $this->validator->validate($json, $constraint);

        $this->buildViolation($message)
            ->setParameter('{{ string }}', $json)
            ->assertRaised();
    }

    public function getInvalidJson()
    {
        return array(
            array('', 'This value is not a valid Json array.'),
            array('abc123', 'This value is not a valid Json array.'),
            array('23', 'The value IS valid Json, but is not an array.'),
            array('[]', 'The value IS a valid Json array, but is empty.'),
        );
    }

}
