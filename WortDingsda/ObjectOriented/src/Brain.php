<?php

namespace ObjWortDings\src;

use ObjWortDings\src\Brain\Word;

class Brain
{
    /**
     * @var Word[] $wordList
     */
    private array $wordList;

    /**
     * @param string $word
     * @return ?Word
     */
    public function getWord(string $word): ?Word
    {
        foreach ($this->wordList as $word) {
            if ($word->getWord() == $word) {
                return $word;
            }
        }
        return null;
    }

    public function addWordInfo(array $newInfo)
    {
        /**
         * $newInfo = [-2 => "word",
         *             -1 => "word",
         *              0 => "word_the_info_is_about",
         *              1 => "word",
         *              2 => "word",
         *          ... n => "word"]
         */
        if (count($newInfo) < 2) {
            log("skipping addWordInfo execution because there is not enough information to add", ["newInfo" => $newInfo]);
            return;
        }
        if (empty($newInfo[0])) {
            log("skipping addWordInfo execution because there is no word to refer to as position 0 (word to add info about)", ["newInfo" => $newInfo]);
        }

        $word = $this->getWord($newInfo[0]);
        if (empty($this->getWord($newInfo[0]))) {
            // create word
            $word = new Word();
            $word->setWord($newInfo[0]);
        }
        foreach ($newInfo as $position => $wordInfo) {
            if ($position == 0) {
                continue;
            }
            $word->addPositionInfo($position, $wordInfo);
        }
        $this->wordList[] = $word;
    }

    public function getRandomWord(): Word
    {
        $rnd = random_int(0, count($this->wordList) -1);
        return $this->wordList[$rnd];
    }

    public function getCalculatedWord(array $context)
    {
        /**
         * $context = [ 0 => "ich",
         *              1 => "habe",
         *              2 => "heute",
         *              3 => "das",
         *          ... n => "geguckt"]
         */
        // TODO: hier weitermachen
        // auswertung mit welchen worten sätze oft enden, und abhängig machen von $context länge, beeinflussbar machen ab wann die wahrscheinlichkeit zum satz beenden steigt durch konstante
    }
}