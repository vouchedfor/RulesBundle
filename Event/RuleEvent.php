<?php

namespace VouchedFor\RulesBundle\Event;

use Symfony\Component\EventDispatcher\Event;

abstract class RuleEvent extends Event
{
    private $action;
    private $actionParameter;

    abstract public function getData();
    abstract public function getName();

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param $action
     * @throws \Exception
     */
    public function setAction($action)
    {
        $actionArray = explode('|', $action);

        switch (count($actionArray)) {
            case 1:
                $this->action = $actionArray[0];
                break;
            case 2:
                $this->action = $actionArray[0];
                $this->actionParameter = $actionArray[1];
                break;
            default:
                throw new \Exception('Action is not in a valid format');
        }
    }

    public function getActionParameter()
    {
        return $this->actionParameter;
    }
}