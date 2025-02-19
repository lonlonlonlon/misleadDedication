<?php

class MainForeground implements IForeground {
    private string $firstColor;
    private string $secondColor;
    private string $thirdColor;
    private array $template;

    //TODO:  y: 24   x: 80   (-1)

    /**
     * @param int $x
     * @param int $y
     * @return string|null
     */
    public function getPixel(int $x, int $y): null|string
    {

        return null;
    }

    /**
     * @return void
     */
    public function nextFrame(): void
    {
        // TODO: Implement nextFrame() method.
    }

    /**
     * @param string $firstColor
     * @param string $secondColor
     * @param string $thirdColor
     * @return void
     */
    public function init(string $firstColor, string $secondColor, string $thirdColor): void
    {
        $this->firstColor = $firstColor;
        $this->secondColor = $secondColor;
        $this->thirdColor = $thirdColor;
        $scriptPath = pathinfo(__FILE__, PATHINFO_DIRNAME).PHP_EOL;
        // load template
        foreach ((file_get_contents($scriptPath.'/../../templates/MainForeground.txt')) as $line) {}
    }
}
