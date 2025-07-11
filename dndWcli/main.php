<?php

include __DIR__ . '/src/IBackground.php';
include __DIR__ . '/src/IForeground.php';
include __DIR__ . '/src/colorDef.php';
include __DIR__ . '/src/Backgrounds/DefaultBackground.php';
include __DIR__ . '/src/Foregrounds/MainForeground.php';

/**
 * Idee: 2 Hauptlayer, Background und Foreground. Background wird nicht ganz gezeichnet, sondern nur die Zeichen die
 * nicht von Foreground gefüllt werden. So könnte man als Hintergrund relativ effizient ein Mandelbrot rendern.
 *
 * Foregrounds sind die "modi" in denen die Anwendung sein kann, sprich für alle Funktionen muss es einen Modus geben.
 * - MainScreen - Spellslots, equipped Spells, Ritual Spells, HP, Kurznotizen(?), Concentration und eventuell dauer von Spells
 * - Spells: changeEquippedSpells / addSpells / changeSpellSlots
 * - Settings?
 * - Journal
 * - Inventar??
 * Generell: DnD Beyond Integration???
 *
 * 24x80
 */

$foreground = new MainForeground();
$foreground->init('', '', '');

for ($x = 0; $x < 80; $x++) {
    for ($y = 0; $y < 24; $y++) {
        echo $foreground->getPixel($x, $y);
    }
    echo "\n";
}