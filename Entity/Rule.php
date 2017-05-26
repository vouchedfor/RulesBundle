<?php

namespace VouchedFor\RulesBundle\Entity;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Doctrine\ORM\Mapping as ORM;

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
     */
    protected $conditions;

    /**
     * @var string
     *
     * @ORM\Column(name="actions", type="string", length=255, nullable=false)
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

        try {
            foreach (json_decode($this->getConditions(), true) as $condition) {
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
        }
        catch (\Exception $e) {
            var_dump($e->getMessage());
            var_dump($this);
            die();
        }

        return $this->getActions();
    }
}
