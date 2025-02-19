<?php

class Background implements IBackground {
    public function getPixel(int $x, int $y): string
    {
        return TERM_BACK_BLACK_DARK.' ';
    }

    public function init(): void
    {
        return;
    }

    public function nextFrame(): void
    {
        return;
    }
}