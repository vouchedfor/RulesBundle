<?php

namespace VouchedFor\RulesBundle\Entity;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Doctrine\ORM\Mapping as ORM;
use VouchedFor\RulesBundle\Validator\Constraints as Assert;

/**
 * Rule
 *
 * @ORM\Table(name="rule", indexes={@ORM\Index(name="rule_event_index", columns={"event"})})
 * @ORM\Entity
 */
class Rule
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="event", type="string", length=50, nullable=false)
     */
    private $event;

    /**
     * @var string
     *
     * @ORM\Column(name="conditions", type="text", nullable=false)
     * @Assert\Json
     */
    protected $conditions;

    /**
     * @var string
     *
     * @ORM\Column(name="actions", type="string", length=255, nullable=false)
     * @Assert\Json
     */
    private $actions;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param string $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getConditionsArray()
    {
        $conditionsArray = json_decode($this->conditions, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Conditions are not in json format');
        }

        return $conditionsArray;
    }

    /**
     * @return string
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param string $actions
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    /**
     * @param $data
     * @return bool|string
     */
    public function evaluate($data)
    {
        $language = new ExpressionLanguage();

        foreach ($this->getConditionsArray() as $condition) {
            if (!$language->evaluate(
                $condition,
                array(
                    'data' => $data
                )
            )
            ) {
                return false;
            }
        }

        return $this->getActions();
    }
}
