<?php
namespace Depotwarehouse\Jeopardy\Tests\Participant;

use Depotwarehouse\Jeopardy\Participant\ContestantFactory;

/**
 * Class ContestantFactoryTest
 *
 * @package Depotwarehouse\Jeopardy\Tests\Participant
 */
class ContestantFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function test_it_deserializes_stdClass()
    {
        $json = '{"name":"mock_contestant","score":100,"key":"h"}';
        $factory = new ContestantFactory();
        $contestant = $factory->createFromObject(json_decode($json));

        $this->assertSame('mock_contestant', $contestant->getName());
        $this->assertEquals(100, $contestant->getScore());
        $this->assertEquals('H', $contestant->getKey());
    }
}
