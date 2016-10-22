<?php

namespace Depotwarehouse\Jeopardy\Participant;

/**
 * Class ContestantFactory
 * @package Depotwarehouse\Jeopardy\Participant
 */
class ContestantFactory
{
    /**
     * Creates a new Contestant instance given an object with a name and optional score property.
     *
     * @param $object
     * @return Contestant
     */
    public function createFromObject($object)
    {
        $name = $object->name;
        $key = strtoupper($object->key);
        $score = (isset($object->score) ? $object->score : 0);
        return new Contestant($name, $score, $key);
    }
}
