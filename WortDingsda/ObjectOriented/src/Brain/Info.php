<?php

namespace ObjWortDings\src\Brain;

class Info
{
    private string $word;
    private int $score = 1;

    public function __construct(string $word = null)
    {
        $this->word = $word;
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * @param string $word
     * @return Info
     */
    public function setWord(string $word): Info
    {
        $this->word = $word;
        return $this;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     * @return Info
     */
    public function setScore(int $score): Info
    {
        $this->score = $score;
        return $this;
    }

    public function addScore()
    {
        $this->score += 1;
    }
}