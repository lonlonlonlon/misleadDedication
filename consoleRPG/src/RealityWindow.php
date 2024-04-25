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

    public function setTopLeft(int $x, int $y)
    {
        $this->topLeftX = $x;
        $this->topLeftY = $y;
    }

    public function setBottomRight(int $x, int $y)
    {
        $this->bottomRightX = $x;
        $this->bottomRightY = $y;
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

    public function setTopLeftX(int $topLeftX): RealityWindow
    {
        $this->topLeftX = $topLeftX;
        return $this;
    }

    public function setTopLeftY(int $topLeftY): RealityWindow
    {
        $this->topLeftY = $topLeftY;
        return $this;
    }

    public function setBottomRightX(int $bottomRightX): RealityWindow
    {
        $this->bottomRightX = $bottomRightX;
        return $this;
    }

    public function setBottomRightY(int $bottomRightY): RealityWindow
    {
        $this->bottomRightY = $bottomRightY;
        return $this;
    }

    public function adjustTo(int $x, int $y)
    {
//        $middle = $this->
    }
}