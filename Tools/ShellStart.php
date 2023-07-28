<?php
echo shell_exec('echo -e ${UCyan}Docker Containers running:${Blue}');
echo shell_exec('docker ps --format \'{{ .ID }}\t{{ .Names }}\'');
echo shell_exec('echo -e ${Color_Off}');

echo "Ich bin Leon\n";
echo "und ich bin Jacky\n";
echo "Denk an die Tulpen!\n";
echo strtotime("12.09.1997 08:00:00").PHP_EOL;
