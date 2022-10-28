<?php

namespace Sudoku;

class Grid
{
    private array $grid = [];

    public function __construct()
    {
        for ($x = 0; $x < 9; $x++) {
            for ($y = 0; $y < 9; $y++) {
                $this->grid[$x][$y] = null;
            }
        }
    }

    public function setVal(int $x, int $y, int $val): self
    {
        $this->grid[$x][$y] = $val;
        return $this;
    }

    public function getVal(int $x, int $y): int
    {
        return $this->grid[$x][$y];
    }

    public function valInRow(int $x, int $val): bool
    {
        foreach ($this->grid[$x] as $value) {
            if ($value == $val) {
                return true;
            }
        }
        return false;
    }

    public function valInCol(int $y, int $val): bool
    {
        foreach ($this->grid as $row) {
            foreach ($row as $yIndex => $value) {
                if ($yIndex == $y && $val == $value) {
                    return true;
                }
            }
        }
        return false;
    }

    public function valInBox($x, $y, $val): bool
    {
        if (!$this->validateValue($x) || !$this->validateValue($y) || !$this->validateValue($val)) {
            throw new \Exception("Invalid value in [x:$x, y:$y, val:$val]");
        }
        $boxX = floor($x/3);
        $boxY = floor($y/3);
        foreach ($this->grid as $xGrid => $row) {
            foreach ($row as $yGrid => $value) {
                if (
                    floor($xGrid/3) == $boxX
                    && floor($yGrid/3) == $boxY
                    && $val == $value
                ) {
                    return true;
                }
            }
        }
        return false;
    }

    private function validateValue(int $coord): bool
    {
        if ($coord < 9 && $coord >= 0) {
            return true;
        }
        return false;
    }

    public function toCsv(): string
    {
        $csvString = "";
        foreach ($this->grid as $xIndex => $row) {
            foreach ($row as $yIndex => $value) {
                if (empty($value)) {
                    $value = 'N';
                }
                $csvString .= $value;
                if ($yIndex == count($row)-1) {
                    $csvString .= PHP_EOL;
                } else {
                    $csvString .= ';';
                }
            }
        }
        return $csvString;
    }
}