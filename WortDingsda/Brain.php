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
        /**
         * $word the word to add info about
         * $info the array consisting of 5 words in order left to right
         */
        $oldInfo = @$this->wordTable[$word];
        if (!$oldInfo) {
            // [
            //                0 => [[$info[0], 1]],
            //                1 => [[$info[1], 1]],
            //                2 => [[$info[2], 1]],
            //                3 => [[$info[3], 1]],
            //                4 => [[$info[4], 1]]
            //            ];
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
            $this->wordTable[$word] = $infoArray;
            return;
        }
        foreach ($oldInfo as $followingWordPosition => &$followWordsWithScore) {
            $wordAlreadyPresent = false;
            foreach ($followWordsWithScore as $currArrayPosOfFollowWord => &$followWordWithScore) {
                if ($followWordsWithScore[0] == @$info[$followingWordPosition]) {
                    $wordAlreadyPresent = true;
                    $followWordsWithScore[$currArrayPosOfFollowWord] = [$followWordsWithScore[0], $followWordsWithScore[1] + 1];
                }
            }
            if (!$wordAlreadyPresent && @$info[$followingWordPosition]) {
                $followWordsWithScore[count($followWordsWithScore)] = [$info[$followingWordPosition], 1];
            }
        }
    }

    public function getNFollowWord(string $word, int $position = 0)
    {
        /**
         * $position may be 0-4, for positions 1-5
         */
        $info = $this->wordTable[$word];
        return $info[$position];
    }
}