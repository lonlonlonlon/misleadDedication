<?php

class AllocatedPlaceholders
{
    private array $name = [];
    private array $verb = [];
    private array $adjektiv = [];
    private array $ort = [];
    private array $nomen = [];
    private array $zeit = [];
    private  array $verb_fortbewegung = [];
    public function __construct(
    )
    {
    }

    public function getWord(string $kind)
    {
        if (!empty($this->$kind) && random_int(0,2) > 0) {
            return $this->$kind[array_rand($this->$kind)]->getTemplateString();
        } else {
            $placeholder = "$$kind$".(count($this->$kind)+1).'$';
            $this->$kind[] = new Placeholder($placeholder);
            return $placeholder;
        }
    }
}