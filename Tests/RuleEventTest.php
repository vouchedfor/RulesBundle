<?php

namespace VouchedFor\RulesBundle\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class RuleEventTest
 * @package VouchedFor\RulesBundle\Tests
 */
class RuleEventTest extends TestCase
{
    /**
     * @dataProvider setActionDataProvider
     */
    public function testSetAction($action, $expectedAction, $expectedActionParameter)
    {
        $ruleEvent = $this
            ->getMockBuilder('VouchedFor\RulesBundle\Event\RuleEvent')
            ->getMockForAbstractClass();

        $ruleEvent->setAction($action);

        $this->assertEquals($expectedAction, $ruleEvent->getAction());
        $this->assertEquals($expectedActionParameter, $ruleEvent->getActionParameter());
    }

    public function setActionDataProvider()
    {
        return [
            ['markAsCancelled', 'markAsCancelled', null],
            ['assignTo|George', 'assignTo', 'George'],
        ];
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Action is not in a valid format
     */
    public function testSetActionTooManyParameter()
    {
        $ruleEvent = $this
            ->getMockBuilder('VouchedFor\RulesBundle\Event\RuleEvent')
            ->getMockForAbstractClass();

        $ruleEvent->setAction('action|2|3');
    }
}
