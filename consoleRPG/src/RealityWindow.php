<?php

namespace consoleRPG\src;

class RealityWindow
{
    private int $topLeftX = 0;
    private int $topLeftY = 0;
    private int $bottomRightX = 0;
    private int $bottomRightY = 0;
    private int $width = 20;
    private int $height = 10;
    private array $centerCoordinates = [20, 10];

    public function __construct(int $width = 40, int $height = 20)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getTopLeftX(): int
    {
        return $this->topLeftX;
    }

    public function getTopLeftY(): int
    {
        return $this->topLeftY;
    }

    public function getBottomRightX(): int
    {
        return $this->bottomRightX;
    }

    public function getBottomRightY(): int
    {
        return $this->bottomRightY;
    }

    public function adjustTo(int $x, int $y)
    {
        $this->centerCoordinates = [$x, $y];
        $this->topLeftX = $x - $this->width/2;
        $this->topLeftY = $y - $this->height/2;
        $this->bottomRightX = $x + $this->width/2 +1;
        $this->bottomRightY = $y + $this->width/2 -2;

        if ($this->topLeftX < 0) {$this->topLeftX = 0;}
        if ($this->topLeftY < 0) {$this->topLeftY = 0;}
        if ($this->bottomRightX < 0) {$this->bottomRightX = 0;}
        if ($this->bottomRightY < 0) {$this->bottomRightY = 0;}
    }
}