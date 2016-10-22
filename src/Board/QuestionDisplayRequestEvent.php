<?php
declare(strict_types=1);
namespace Depotwarehouse\Jeopardy\Board;

use League\Event\AbstractEvent;

/**
 * Class QuestionDisplayRequestEvent
 * @package Depotwarehouse\Jeopardy\Board
 */
class QuestionDisplayRequestEvent extends AbstractEvent
{
    /**
     * @var string
     */
    protected $categoryName;

    /**
     * @var int
     */
    protected $value;

    /**
     * QuestionDisplayRequestEvent constructor.
     * @param string $categoryName
     * @param int $value
     */
    public function __construct(string $categoryName, int $value)
    {
        $this->categoryName = $categoryName;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
