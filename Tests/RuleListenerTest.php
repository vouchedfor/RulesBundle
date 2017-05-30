<?php

namespace VouchedFor\RulesBundle\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VouchedFor\RulesBundle\Entity\Rule;
use VouchedFor\RulesBundle\Event\RuleEvent;
use VouchedFor\RulesBundle\EventListener\RuleListener;

/**
 * Class RuleListenerTest
 * @package VouchedFor\RulesBundle\Tests
 */
class RuleListenerTest extends TestCase
{
    public function testHandleEvent()
    {
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rules = [];

        $rule = new Rule();
        $rule->setConditions('["data.age < 67"]');
        $rule->setActions('["email:new-user","user-status:pending"]');

        $rules[] = $rule;

        $repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue($rules));

        $em->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($repository));

        $dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $ruleEvent = $this
            ->getMockBuilder(RuleEvent::class)
            ->getMockForAbstractClass();

        $data = new \stdClass();
        $data->age = 65;

        $ruleEvent->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($data));

        $dispatcher->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(['email:new-user'], ['user-status:pending']);

        $ruleListener = new RuleListener($em, $dispatcher);
        $ruleListener->handleEvent($ruleEvent);
    }
}
