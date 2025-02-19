<?php

interface IBackground {
    public function getPixel(int $x, int $y): string;
    public function init(): void;
    public function nextFrame(): void;
}
