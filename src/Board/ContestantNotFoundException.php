<?php
namespace Depotwarehouse\Jeopardy\Board;

use Depotwarehouse\Jeopardy\Participant\Contestant;
use Illuminate\Support\Collection;

/**
 * Class ContestantNotFoundException
 *
 * @package Depotwarehouse\Jeopardy\Board
 */
class ContestantNotFoundException extends \Exception
{
    /**
     * ContestantNotFoundException constructor.
     *
     * @param null            $message
     * @param int             $code
     * @param \Exception|null $previous
     * @param Collection|null $availableContestants
     */
    public function __construct(
        $message = null,
        $code = 0,
        \Exception $previous = null,
        Collection $availableContestants = null
    ) {
        if ($availableContestants !== null) {
            $availableContestantNames = $availableContestants->map(function (Contestant $contestant) {
                                return $contestant->getName();
                            });
            $message .= PHP_EOL.'Available contestants: '.implode(', ', $availableContestantNames->toArray());
        }

        parent::__construct($message, $code, $previous);
    }
}
