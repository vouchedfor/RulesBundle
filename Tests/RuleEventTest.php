<?php

namespace VouchedFor\RulesBundle\Tests;

use PHPUnit\Framework\TestCase;
use Exception;

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

    public function testSetActionTooManyParameter()
    {
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Action is not in a valid format');
        
        $ruleEvent = $this
            ->getMockBuilder('VouchedFor\RulesBundle\Event\RuleEvent')
            ->getMockForAbstractClass();

        $ruleEvent->setAction('action|2|3');

    }
}
