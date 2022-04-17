<?php

namespace BiebelBier\SpookyBibleStuff;

class BibleChapter
{
    private array $files;

    public function __construct(
        private string $title
    )
    {
    }

    public function getTitle()
    {
        return $this->title; // xD
    }

    public function addFile(BibleFile $file)
    {
        $this->files[] = $file;
        return $this;
    }

    public function getFiles() {
        return $this->files;
    }
}