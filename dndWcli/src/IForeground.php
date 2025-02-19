<?php

interface IForeground {
    public function getPixel(int $x, int $y): null|string;
    public function nextFrame(): void;

    public function init(string $firstColor, string $secondColor, string $thirdColor): void;
}