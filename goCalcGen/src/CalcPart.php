<?php

namespace FractalHuntBruteForce\Stuff;

class CalcPart
{

    /**
     * @param int $numComponents
     * @param string $template
     */
    public function __construct(
        private int $numComponents,
        private string $template)
    {
    }

    public function getNumComponents(): int
    {
        return $this->numComponents;
    }

    public function setNumComponents(int $numComponents): void
    {
        $this->numComponents = $numComponents;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

}