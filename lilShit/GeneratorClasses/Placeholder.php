<?php

class Placeholder
{
    public function __construct(
        private string $templateString,
        private array $tags = []
    )
    {
    }

    /**
     * @return string
     */
    public function getTemplateString(): string
    {
        return $this->templateString;
    }

    /**
     * @param string $templateString
     * @return Placeholder
     */
    public function setTemplateString(string $templateString): Placeholder
    {
        $this->templateString = $templateString;
        return $this;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     * @return Placeholder
     */
    public function setTags(array $tags): Placeholder
    {
        $this->tags = $tags;
        return $this;
    }

    public function addTag(string $tag)
    {
        $this->tags[] = $tag;
    }
}