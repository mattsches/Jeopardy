<?php
declare(strict_types=1);
namespace Depotwarehouse\Jeopardy\Board;

use Depotwarehouse\Jeopardy\Board\Question\FinalJeopardy\State;
use Depotwarehouse\Jeopardy\Buzzer\BuzzerResolution;
use Depotwarehouse\Jeopardy\Buzzer\BuzzerStatus;
use Depotwarehouse\Jeopardy\Buzzer\Resolver;
use Depotwarehouse\Jeopardy\Participant\Contestant;
use Illuminate\Support\Collection;

/**
 * Class Board
 * @package Depotwarehouse\Jeopardy\Board
 */
class Board
{
    /**
     * A collection of contestants.
     * @var Collection
     */
    protected $contestants;

    /**
     * A collection of Categories.
     * @var Collection
     */
    protected $categories;

    /**
     * Our clue for final Jeopardy.
     * @var State
     */
    protected $finalJeopardyState;

    /**
     * The current status of the buzzer.
     * @var BuzzerStatus
     */
    protected $buzzerStatus;

    /**
     * The buzzer resolver which resolves who won a particular buzz.
     * @var Resolver
     */
    protected $resolver;

    /**
     * @param Contestant[]|Collection $contestants
     * @param Category[]|Collection $categories
     * @param Resolver $resolver
     * @param BuzzerStatus $buzzerStatus
     * @param State $final
     */
    public function __construct($contestants, $categories, Resolver $resolver, BuzzerStatus $buzzerStatus, State $final)
    {
        $this->contestants = ($contestants instanceof Collection) ? $contestants : new Collection($contestants);
        $this->categories = new Collection($categories);
        $this->resolver = $resolver;
        $this->buzzerStatus = $buzzerStatus;
        $this->finalJeopardyState = $final;
    }

    /**
     * @return BuzzerStatus
     */
    public function getBuzzerStatus(): BuzzerStatus
    {
        return $this->buzzerStatus;
    }

    /**
     * @param BuzzerStatus $buzzerStatus
     * @return Board
     */
    public function setBuzzerStatus(BuzzerStatus $buzzerStatus): Board
    {
        $this->buzzerStatus = $buzzerStatus;
        return $this;
    }

    /**
     * @return Resolver
     */
    public function getResolver(): Resolver
    {
        return $this->resolver;
    }

    /**
     * Resolves the current buzzer competition and returns the resolution.
     * As a side-effect, this will also disable the buzzer.
     *
     * @return BuzzerResolution
     */
    public function resolveBuzzes(): BuzzerResolution
    {
        $resolution = $this->resolver->resolve();
        $this->buzzerStatus->disable();
        return $resolution;
    }

    /**
     * @param Contestant $contestant
     * @param            $value
     *
     * @return Contestant
     * @throws \Depotwarehouse\Jeopardy\Board\ContestantNotFoundException
     */
    public function addScore(Contestant $contestant, $value): Contestant
    {
        /** @var Contestant $c */
        $c = $this->getContestants()->first(function(Contestant $c) use ($contestant) {
            return $c->getName() === $contestant->getName();
        });

        if ($c === null) {
            //TODO logging.
            throw new ContestantNotFoundException(
                "Unable to find contestant with name {$contestant->getName()}", 0, null, $this->getContestants()
            );
        }

        $c->addScore($value);
        return $contestant;
    }

    /**
     * Gets the first question that matches both the category and value.
     * @param $categoryName
     * @param int $value
     * @return Question
     * @throws QuestionNotFoundException
     */
    public function getQuestionByCategoryAndValue($categoryName, $value): Question
    {
        /** @var Category $category */
        $category = $this->categories->first(function (Category $category) use ($categoryName) {
            return $category->getName() === $categoryName;
        });
        if ($category === null) {
            throw new QuestionNotFoundException;
        }
        $question = $category->getQuestions()->first(function (Question $question) use ($value) {
            return $question->getValue() === $value;
        });
        if ($question === null) {
            throw new QuestionNotFoundException;
        }
        return $question;
    }

    /**
     * @return Collection
     */
    public function getContestants(): Collection
    {
        return $this->contestants;
    }

    /**
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @return State
     */
    public function getFinalJeopardy(): State
    {
        return $this->finalJeopardyState;
    }

    /**
     * @return Question\FinalJeopardyClue
     */
    public function getFinalJeopardyClue(): Question\FinalJeopardyClue
    {
        return $this->finalJeopardyState->getClue();
    }
}
