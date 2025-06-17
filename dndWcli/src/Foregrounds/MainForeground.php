<?php

class MainForeground implements IForeground
{
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
        try {
            return $this->template[$y][$x];
        } catch (Exception $e) {
            return ' ';
        }
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
        set_error_handler(function (
            ...$stuff
        ): bool {
            return true;
        });
        $this->firstColor = $firstColor;
        $this->secondColor = $secondColor;
        $this->thirdColor = $thirdColor;
        $scriptPath = pathinfo(__FILE__, PATHINFO_DIRNAME);
        // load template
        foreach (explode("\n", file_get_contents($scriptPath . '/../../templates/MainForeground.txt')) as $lineIndex => $line) {
            foreach (str_split($line) as $charIndex => $char) {
//                if ($char === ' ') {
//                    continue;
//                }
                $this->template[$lineIndex][$charIndex] = $char;
            }
        }
    }
}
