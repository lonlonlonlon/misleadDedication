<?php

namespace ObjWortDings\src\Brain;

/**
 *
 */
class Position
{
    /**
     * @var array
     */
    private array $infoList;

    /**
     * @return array
     */
    public function getInfoList(): array
    {
        return $this->infoList;
    }

    /**
     * @param Info $info
     * @return void
     */
    public function addInfoToInfoList(Info $info)
    {
        $infoAlreadyPresent = false;
        foreach ($this->infoList as $ownInfo) {
            if ($ownInfo->getWord() == $info->getWord()) {
                $ownInfo->addScore();
                $infoAlreadyPresent = true;
            }
        }
        if (!$infoAlreadyPresent) {
            $this->infoList[] = $info;
        }
    }

}