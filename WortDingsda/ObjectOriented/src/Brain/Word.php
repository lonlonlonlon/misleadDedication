<?php

namespace ObjWortDings\src\Brain;


class Word
{
    private string $word;

    /**
     * @var Position[] $infoList
     */
    private array $positionList;

    public function setWord(string $word)
    {
        $this->word = $word;
    }

    public function getWord()
    {
        return $this->word;
    }

    public function addPositionInfo(int $position, string $wordInfo)
    {
        if (empty($this->positionList[$position])) {
            $this->positionList[$position] = new Position();
        }

        $position = $this->positionList[$position];
        $info = new Info($wordInfo);
        $position->addInfoToInfoList($info);
    }

}