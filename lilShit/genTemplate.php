<?php

namespace TemplateGenerator;

use AllocatedPlaceholders;

foreach (glob(__DIR__."/GeneratorClasses/*.php") as $filename)
{
    if (str_starts_with($filename, '.')) {
        continue;
    }
    include $filename;
}

function genSentence(\AllocatedPlaceholders $allocatedPlaceholders) {
    $template = "";
    $start = getRandomTemplateStart();
    $start = fillTemplateWithPlaceholders($start, $allocatedPlaceholders);
    $template = $start;
    $maxIntermediate = 3;
    $currentIntermediates = 0;
    do {
        $currentIntermediates++;
        $intermediate = getRandomIntermediateTemplateString();
        $intermediate = fillTemplateWithPlaceholders($intermediate, $allocatedPlaceholders);
        $template .= $intermediate;
    } while ($currentIntermediates < $maxIntermediate && random_int(0,2) > 1);
    $template .= ". ";
    return $template;
}

function fillTemplateWithPlaceholders(string $string, \AllocatedPlaceholders $allocatedPlaceholders)
{
    $result = "";
    $arr = explode("__", $string);
    foreach ($arr as $i => $part) {
        if  ($i % 2 == 0) {
            // normal text
            $result .= $part;
        }
        if  ($i % 2 == 1) {
            // placeholder name
            $result .= $allocatedPlaceholders->getWord($part);
        }
    }
    return $result;
}

function getRandomTemplateStart()
{
    $starts = [
        "Als __zeit__ begann __name__ vorsichtig und __adjektiv__ den __nomen__ zu __verb__, ",
        "__name__ ",
        "Um __zeit__ ",
        "__name__ __verb__ ",
        "Ganz __adjektiv__ begann __name__ "
    ];
    return $starts[array_rand($starts)];
}

function getRandomIntermediateTemplateString() {
    $intermediates = [
        ", __verb_fortbewegung__ __name__ zu __ort__ ",
        ", um bei __ort__ __adjektiv__ zu __verb__ ",
        ", mit __nomen__ ganz __adjektiv__ __verb__ ",
        ", weil um __zeit__ __name__ unbedingt __verb__ ",
    ];
    return $intermediates[array_rand($intermediates)];
}

$template = "";
$allocatedPlaceholders = new AllocatedPlaceholders();
for ($i = 0; $i < 20; $i++) {
    $template .= genSentence($allocatedPlaceholders);
}
echo $template;