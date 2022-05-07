<?php

class DisplayWorker extends Thread
{
    private Threaded $shared;

    public function __construct(Threaded $shared) {
        $this->shared = $shared;
    }

    public function run()
    {
        if (count($this->shared) > 0) {
            $toShow = $this->shared->pop();
            system('clear');
            $toShow->render();
        }
    }
}

class Pic {
    private array $lines;
    private Colorer $colorer;

    public function constructFromOtherPic(Pic $pic)
    {
        $this->colorer = $pic->getColorer();
        $this->lines = $pic->getLines();
    }

    public function render()
    {
        foreach ($this->lines as $line) {
            echo($this->colorer->colorLine($line) . "\n");
        }
    }

    public function getColorer(): Colorer
    {
        return $this->colorer;
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function setColorer(Colorer $colorer)
    {
        $this->colorer = $colorer;
    }

    public function setLines(array $lines)
    {
        $this->lines = $lines;
    }

    public function addLine(string $line)
    {
        $this->lines[] = $line;
    }
}

class Colorer {
    private mixed $coloringObj;

    public function __construct(string $coloringFileName)
    {
        $this->coloringObj = json_decode(file_get_contents($coloringFileName));
    }

    public function colorLine(string $line)
    {
        foreach (get_object_vars($this->coloringObj) as $coloringKey) {
            $line = preg_replace("/" . $coloringKey . "/", $this->coloringObj->{$coloringKey}, $line);
        }

        return $line;
    }
}

$shared = new Threaded();
$displayWorker = new DisplayWorker($shared);
$colorer = new Colorer('testResources/coloring.json');
$start = 1;
while (1){
    $pic = new Pic;
    $pic->setColorer($colorer);
    $add = $start;
    for ($x = 0; $x < 39; $x++) {
        $line = $add;
        $add += 1;
        if ($add == 5) {$add = 1;}
    }
    $start += 1;
    if ($start == 5) {$start = 1;}
    $pic->addLine($line);
    $shared[] = $pic;
}
