<?php

namespace WortDingsda;

class Brain
{
    /**
     * Array Structure
     * [
     *      "word" => [
     *          0 => [["word", 1], ["word", 11], ["word", 44], ["word", 88], ["word", 32], n],
     *          1 => [["word", 4], ["word", 29], ["word", 22], ["word", 7], ["word", 2], n],
     *          2 => [["word", 32], ["word", 29], ["word", 22], ["word", 7], ["word", 2], n],
     *          3 => [["word", 15], ["word", 5], ["word", 7], ["word", 8], ["word", 34], n],
     *          4 => [["word", 5], ["word", 29], ["word", 22], ["word", 7], ["word", 2], n]
     *      ]
     * ]
     * zu jedem Wort bis zu 5 worte die oft an n'ter stelle danach kommen mit ihrer Häufigkeit im bisherigen Training
     */

    private array $wordTable;

    public function __construct()
    {
        $this->wordTable = [];
    }

    public function getWordInfo(string $word): array
    {
        return $this->wordTable[$word];
    }

    public function getRandomWord(): string
    {
        $tmp = array_keys($this->wordTable);
        return $tmp[random_int(0,count($this->wordTable)-1)];
    }

    public function addWordInfo(string $word, array $info)
    {
        $word = strtolower($word);
        /**
         * $word the word to add info about
         * $info = ["word", "word", "word", "word", "word"] (array pos 0 = follow pos 1)
         */
        $oldInfo = @$this->wordTable[$word];
        if (!$oldInfo) {
            $infoArray = [];
            if (@$info[0]) {
                $infoArray[0] = [$info[0], 1];
                if (@$info[1]) {
                    $infoArray[1] = [$info[1], 1];
                    if (@$info[2]) {
                        $infoArray[2] = [$info[2], 1];
                        if (@$info[3]) {
                            $infoArray[3] = [$info[3], 1];
                            if (@$info[4]) {
                                $infoArray[4] = [$info[4], 1];
                            }
                        }
                    }
                }
            }
            var_dump($infoArray);
            $this->wordTable[$word] = $infoArray;
            return;
        } else {
            foreach ($oldInfo as $followWordPosition => $oldInfoToPosition) {
                // hier weiter
                // achtung, villeicht exits(); verstreut
            }
        }
    }

    public function getNFollowWords(string $word, int $position = 0)
    {
        /**
         * $position may be 0-4, for positions 1-5
         * returns [["word", 65], ["word", 23], ...n]
         */
        $word = strtolower($word);
        $info = $this->wordTable[$word];
        var_dump($info);
        exit();
        return $info[$position];
    }

    public function getCalculatedFollower(array $lastWords)
    {
        $candidates = [];
        foreach ($lastWords as $index => $pastWord) {
            // von hinten nach vorne : ["hallo", ",", "wie", "geht", "es"] => abs(index-4)
            $pastWordInfo = $this->getNFollowWords($pastWord, abs($index-4));
            $candidates[] = $this->randScoreBasedSelection($pastWordInfo);
        }
        exit();
    }

    private function randScoreBasedSelection(array $info): array
    {
        /**
         * [["word", 65], ["word", 23], ...n] = $info
         */
        write("count info: ".count($info));
        if (count($info) == 1) {
            return $info[0];
        }
        var_dump($info);
        $winner = array_shift($info);
        foreach ($info as $singleInfo) {
            $scoreAdd = $winner[1] + $singleInfo[1];
            $randInt = random_int(0, $scoreAdd);
            if ($randInt > $winner[1]) {
                $winner = $singleInfo;
            }
        }
        var_dump($winner);
        return [];
    }
}