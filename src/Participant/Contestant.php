<?php

namespace Depotwarehouse\Jeopardy\Participant;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Contestant implements Arrayable, Jsonable
{

    /** @var  string */
    protected $name;

    protected $score;

    /**
     * @var string
     */
    protected $key;

    /**
     * Contestant constructor.
     * @param string $name
     * @param int $score
     * @param string $key
     */
    public function __construct($name, $score = 0, $key = null)
    {
        $this->name = $name;
        $this->key = $key;
        $this->score = $score;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    public function addScore($value)
    {
        $this->score += $value;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'score' => $this->getScore(),
            'key' => $this->getKey(),
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

}
