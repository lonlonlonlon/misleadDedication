#!/usr/bin/php
<?php
# eigenartiger Fehler der bisher bei einer Datei im RAB aufgefallen ist:
# die Datei wurde von kate und file als ISO-8859-15 identifiziert, konnte
# aber weder von iconv noch von der ForceUTF8 library im RAB korrekt dekodiert werden.
# Die lösung war als Einleseformat ISO-8859-1 anzugeben, dadurch konnte die Datei
# fehlerfrei konvertiert werden, umlaute wurden auch korrekt übersetzt.
$file = $argv[1];
if (!str_contains($file, '\ ') && str_contains($file, ' ')) {
    $file = str_replace(' ', '\ ', $file);
}
shell_exec("iconv -f ISO-8859-15 -t UTF-8 $file --output=$file");
