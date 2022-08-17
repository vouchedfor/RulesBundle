<?php

namespace VouchedFor\RulesBundle\Tests;

use PHPUnit\Framework\TestCase;
use VouchedFor\RulesBundle\Entity\Rule;
use Exception;

/**
 * Class RuleTest
 * @package VouchedFor\RulesBundle\Tests
 */
class RuleTest extends TestCase
{
    public function testId()
    {
        $rule = new Rule();

        $this->assertEquals(null, $rule->getId());
    }

    public function testEvent()
    {
        $rule = new Rule();
        $rule->setEvent('user:registered');

        $this->assertEquals('user:registered', $rule->getEvent());
    }

    public function testName()
    {
        $rule = new Rule();
        $rule->setName('User Registration');

        $this->assertEquals('User Registration', $rule->getName());
    }

    public function testConditions()
    {
        $rule = new Rule();
        $rule->setConditions('["data.age > 65"]');

        $this->assertEquals('["data.age > 65"]', $rule->getConditions());
    }

    public function testEvaluate()
    {
        $rule = new Rule();
        $rule->setConditions('["data.age > 65"]');
        $rule->setActions('["mark-as-retired"]');

        $data = new \stdClass();
        $data->age = 70;

        $this->assertEquals('["mark-as-retired"]', $rule->evaluate($data));
    }

    public function testEvaluateFalse()
    {
        $rule = new Rule();
        $rule->setConditions('["data.age > 65"]');

        $data = new \stdClass();
        $data->age = 40;

        $this->assertFalse($rule->evaluate($data));
    }

    public function testEvaluateTwoRules()
    {
        $rule = new Rule();
        $rule->setConditions('["data.age > 65", "data.numberOfHouses == 2"]');
        $rule->setActions('["mark-as-retired-with-second-home"]');

        $data = new \stdClass();
        $data->age = 70;
        $data->numberOfHouses = 2;

        $this->assertEquals('["mark-as-retired-with-second-home"]', $rule->evaluate($data));
    }

    public function testEvaluateTwoRulesFailingOne()
    {
        $rule = new Rule();
        $rule->setConditions('["data.age > 65", "data.numberOfHouses == 2"]');
        $rule->setActions('["mark-as-retired-with-second-home"]');

        $data = new \stdClass();
        $data->age = 70;
        $data->numberOfHouses = 1;

        $this->assertFalse($rule->evaluate($data));
    }

    public function testInvalidCondition()
    {
                
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Conditions are not in json format');
        $rule = new Rule();
        $rule->setConditions('Not valid json');

        $data = new \stdClass();
        $data->age = 70;

        $rule->evaluate($data);
    }
}
