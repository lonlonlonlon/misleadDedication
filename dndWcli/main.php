<?php

foreach (glob("src/*.php") as $filename)
{
//    if ($filename === 'src/inputListener.php') {
//        continue;
//    }
    include $filename;
}

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

