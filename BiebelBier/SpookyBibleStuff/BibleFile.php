<?php

namespace BiebelBier\SpookyBibleStuff;

class BibleFile
{

    private array $lines;

    public function __construct(
    )
    {
    }

    public function addLine(string $line)
    {
        $this->lines[] = $line;
        return $this;
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function setLines(array $lines) {
        $this->lines = $lines;
    }
}