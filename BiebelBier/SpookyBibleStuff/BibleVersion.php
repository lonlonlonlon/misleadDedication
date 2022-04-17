<?php

namespace BiebelBier\SpookyBibleStuff;

class BibleVersion
{
    private array $chapters;
    private array $history;

    /**
     * @return array
     */
    public function getHistory(): array
    {
        return $this->history;
    }

    /**
     * @param array $history
     * @return BibleVersion
     */
    public function setHistory(array $history): BibleVersion
    {
        $this->history = $history;
        return $this;
    }

    public function addHistory(string $history)
    {
        $this->history[] = $history;
    }

    /**
     * @return array
     */
    public function getChapters(): array
    {
        return $this->chapters;
    }

    /**
     * @param array $chapters
     * @return BibleVersion
     */
    public function setChapters(array $chapters): BibleVersion
    {
        $this->chapters = $chapters;
        return $this;
    }

    public function __construct(
        private string $name,
        private string $description,
    )
    {
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }
}