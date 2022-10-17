<?php

namespace VouchedFor\RulesBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VouchedFor\RulesBundle\Entity\Rule;
use VouchedFor\RulesBundle\Event\RuleEvent;

class RuleListener
{
    private $em;
    private $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function handleEvent(RuleEvent $event)
    {
        $rules = $this->findRulesByEvent($event->getName());
        $this->applyRules($event, $rules);
    }

    /**
     * @param RuleEvent $event
     * @param array $rules
     */
    private function applyRules(RuleEvent $event, array $rules) {
        $data = $event->getData();

        foreach ($rules as $rule) {
            /* @var Rule $rule */
            if ($rule->evaluate($data)) {
                $this->dispatchActionEvents($rule, $event);
            }
        }
    }

    private function findRulesByEvent($eventName)
    {
        return $this->em->getRepository(Rule::class)->findBy(['event' => $eventName]);
    }

    private function dispatchActionEvents(Rule $rule, RuleEvent $event)
    {
        foreach (json_decode($rule->getActions(), true) as $action) {
            $event->setRuleName($rule->getName());
            $event->setAction($action);
            $this->dispatchActionEvent($event);
        }
    }

    private function dispatchActionEvent(RuleEvent $event)
    {
        $this->dispatcher->dispatch($event, $event->getAction());
    }
}
