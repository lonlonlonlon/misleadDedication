<?php

namespace BiebelBier\SpookyBibleStuff;

include_once "BibleChapter.php";
include_once "BibleFile.php";
include_once "BibleVersion.php";


class BibleCli
{
    public function __construct($args)
    {
        $this->menue();
    }

    private function menue()
    {
        $handle = fopen ("php://stdin","r");
        while (1) {
            system('clear');
            $this->write('Biebel Kommandozeilentool Version 0.1', 'purple');
            $this->write("1. importiere Biebel", 'blue');
            $this->write("2. bearbeite Biebel", 'yellow');
            $this->write("3. gebe Biebel zum lesen aus", 'green');
            $this->write("4. Flucht!", 'red');

            echo("\t");
            $line = fgets($handle);
            switch ($line) {
                case ('1'):
                    $this->readBible();
                    break;
                case ('2'):
                    $this->editBible($handle);
                    break;
                case ('3'):
                    $this->printBibleMenue($handle);
                    break;
                case ('4'):
                    $this->write("Gute Ute! Chiao Kakao :)", 'cyan');
                    exit();
            }
        }
    }

    private function write(string $string, string $color = 'off')
    {
        if($color === 'red') {
            echo("\033[0;31m$string\033[0m\n");
            return;
        }
        if($color === 'green') {
            echo("\033[0;32m$string\033[0m\n");
            return;
        }
        if($color === 'yellow') {
            echo("\033[0;33m$string\033[0m\n");
            return;
        }
        if($color === 'blue') {
            echo("\033[0;34m$string\033[0m\n");
            return;
        }
        if($color === 'black') {
            echo("\033[0;30m$string\033[0m\n");
            return;
        }
        if($color === 'purple') {
            echo("\033[0;35m$string\033[0m\n");
            return;
        }
        if($color === 'cyan') {
            echo("\033[0;36m$string\033[0m\n");
            return;
        }
        if($color === 'white') {
            echo("\033[0;37m$string\033[0m\n");
            return;
        }
        echo($string . "\n");
    }

    private function readBible()
    {
        system('clear');
        $handle = fopen ("php://stdin","r");

        $folderNameArray = scandir('./');
        $this->write("Wähle den Biebelordner", 'cyan');

        foreach ($folderNameArray as $key => $entry) {
            if($entry === '.' || $entry === '..') {
                continue;
            }
            $this->write("$entry", 'green');
        }

        $folder = trim(fgets($handle));
        if (!is_dir($folder)) {
            $this->write('seh ich nicht.');
            fgets($handle);
            $this->readBible();
        }
        $this->write("Eine Beschreibung? :");
        $description = fgets($handle);
        // read bible!
        $folderNameArray = scandir($folder);
        $chapters = [];
        $bible = new BibleVersion($folder, $description);
        foreach($folderNameArray as $chapter) {
            if ($chapter === '.' || $chapter === '..') {
                continue;
            }
            $chapters[] = $this->fillChapter($folder.'/'.$chapter.'/', $chapter);
        }
        $bible->setChapters($chapters);
        $bible->addHistory("Erstellung aus Ordner $folder");
        $bible->addHistory("Beschreibung hinzugefügt: $description");
        $this->write("so, die Biebel ist gelesen und wird unter $folder.bib gespeichert.", 'yellow');
        file_put_contents("$folder.bib", serialize($bible));
        $this->write("Enter drücken zum Ford fahren", 'blue');
        fgets($handle);
    }

    private function fillChapter(string $pathToChapter, string $title)
    {
        $fileList = scandir($pathToChapter);
        $chapter = new BibleChapter($title);
        foreach ($fileList as $index => $file) {
            if($file !== '.' && $file !== '..') {
                $chapter->addFile($this->parseSingleFile($pathToChapter . $file));
            }
        }
        return $chapter;
    }

    private function parseSingleFile(string $pathToFile)
    {
        $content = file_get_contents($pathToFile);
        $content = preg_replace('/\<noscript\>\<p\>JavaScript is required\.\<\/p\>\<\/noscript\>/', "", $content);// <noscript><p>JavaScript is required.</p></noscript>
        $content = explode('<p>', $content)[1];
        $content = explode('</p>', $content)[0];
        $handle = fopen('data://text/plain,' . $content,'r');
        $firstLine = explode('</span>', fgets($handle));
        $bibleFile = new BibleFile();
        $bibleFile->addLine($firstLine[1]);
        while (($line = fgets($handle)) !== false) {
            $line = explode('<br /><span class="verse" id="', $line)[1];
            $line = explode('</span>', $line)[1];
            $bibleFile->addLine($line);
        }
        return $bibleFile;
    }

    private function editBible($handle)
    {
        try {
            system('clear');
            $this->write("welche denn? ", 'blue');
            foreach (scandir("./") as $index => $value) {
                if (str_ends_with($value, '.bib')) {
                    $this->write("$index. $value", 'green');
                }
            }
            $this->write("\t");
            $choice = trim(fgets($handle));
            if (str_ends_with(scandir('./')[$choice], '.bib')) {
                $bible = unserialize(file_get_contents(scandir('./')[$choice]));
            }
        } catch (\Exception $exception) {
            system('clear');
            $this->write("Da ist wohl etwas schief gegangen, sry :(", 'red');
            $date = new \DateTime('now');
            $date = $date->format('Y-m-d_h:m:s');
            file_put_contents("BiebelCliCrashReport_$date.log", serialize($exception));
            exit();
        }
        if(empty($bible)) {
            $this->write("die gibbet nich!", 'red');
            $this->write("Enter zum fortfahren.", 'white');
            fgets($handle);
            return;
        }
        while(1) {
            system('clear');
            $this->write("ok, was soll denn so bearbeitet werden?", 'yellow');
            $this->write("1. Wörter ersetzen", 'green');
            $this->write("2. Beschreibung ändern", 'blue');
            $this->write("3. häufigste Wörter anzeigen", 'cyan');
            $this->write("4. Speichern", 'yellow');
            $this->write("5. Hauptmenü", 'red');
            $this->write("\t");
            $input = trim(fgets($handle));
            switch ($input) {
                case '1':
                    $this->replaceWords($handle, $bible);
                    break;
                case '2':
                    $this->rewriteDescription($handle, $bible);
                    break;
                case '3':
                    $this->showMostCommonWords($handle, $bible);
                case '4':
                    $this->saveBible($handle, $bible);
                case '5':
                    return;
            }
        }
    }

    private function replaceWords($handle, $bible)
    {
        /** @var BibleVersion $bible */
        system('clear');
        $this->write("Sooos, ein Spaßvogel also, welches Wort soll denn Ersetzt werden?", 'purple');
        $wordToReplace = trim(fgets($handle));
        $this->write("Und durch welches Wort soll $wordToReplace ersetzt werden?", 'cyan');
        $replacementWord = trim(fgets($handle));

        foreach ($bible->getChapters() as $chapter) {
            /** @var BibleChapter $chapter*/
            foreach ($chapter->getFiles() as $file) {
                /** @var BibleFile $file */
                $newLines = [];
                foreach ($file->getLines() as $line) {
                    $newLines[] = preg_replace('/' . $wordToReplace . '/', $replacementWord, $line);
                }
                $file->setLines($newLines);
            }
        }
        $bible->addHistory("Wort $wordToReplace mit $replacementWord ersetzt.");

        $this->write("ok, is gemacht :)", 'green');
        $this->write("Enter zum weitergehen", 'blue');
        fgets($handle);
    }

    private function printBible($handle, BibleVersion $bible)
    {
        system('clear');
        $this->write("So, dann gib mal Namen ay xD", 'purple');
        $in = trim(fgets($handle));
        while(is_file($in.".txt")){
            system('clear');
            $this->write("dat gibbet schon, mach ma anders.", 'yellow');
            $in = trim(fgets($handle));
        }
        $outStream = fopen($in.".txt", 'w');
        fwrite($outStream, $bible->getDescription() . "\n");
        foreach ($bible->getChapters() as $chapter) {
            /** @var BibleChapter $chapter*/
            fwrite($outStream, "\n\nKapitel " . $chapter->getTitle() . "\n\n");
            foreach ($chapter->getFiles() as $file) {
                /** @var BibleFile $file */
                fwrite($outStream, "\nNächster Abschnitt\n\n");
                foreach ($file->getLines() as $line) {
                    fwrite($outStream, trim($line)."\n");
                }
            }
        }
        $histString = "\nHistorie dieser Biebelversion:\n\n" . implode("\n", $bible->getHistory()) . "\n\nBie Diebel bearbeitet mit so nem komischen php cli tool";
        fwrite($outStream, $histString);
        fclose($outStream);
        $this->write("is gemacht! viel Spaß beim lesen xD", 'purple');
        $this->write("drückst du Enter geht es weiter", 'yellow');
        fgets($handle);
    }

    private function saveBible($handle, BibleVersion $bible)
    {
        system('clear');
        $this->write("So, dann gib mal Namen für bie Diebel", 'cyan');
        $in = trim(fgets($handle));
        while(is_file($in.".bib")){
            system('clear');
            $this->write("dat gibbet schon, mach ma anders.", 'green');
            $in = trim(fgets($handle));
        }

        file_put_contents("$in.bib", serialize($bible));
        $this->write("is gemacht, Enter zum fortfahren ^^", 'yellow');
        fgets($handle);
    }

    private function printBibleMenue($handle)
    {
        try {
            system('clear');
            $this->write("welche denn? ", 'blue');
            foreach (scandir("./") as $index => $value) {
                if (str_ends_with($value, '.bib')) {
                    $this->write("$index. $value", 'green');
                }
            }
            $this->write("\t");
            $choice = trim(fgets($handle));
            if (str_ends_with(scandir('./')[$choice], '.bib')) {
                $bible = unserialize(file_get_contents(scandir('./')[$choice]));
            }
        } catch (\Exception $exception) {
            system('clear');
            $this->write("Da ist wohl etwas schief gegangen, sry :(", 'red');
            $date = new \DateTime('now');
            $date = $date->format('Y-m-d_h:m:s');
            file_put_contents("BiebelCliCrashReport_$date.log", serialize($exception));
            exit();
        }
        if(empty($bible)) {
            $this->write("die gibbet nich!", 'red');
            $this->write("Enter zum fortfahren.", 'white');
            fgets($handle);
            return;
        }
        $this->printBible($handle, $bible);
    }

    private function rewriteDescription($handle, mixed $bible)
    {
        /** @var BibleVersion $bible */
        system('clear');
        $this->write("Die aktuelle Beschreibung lautet:", 'green');
        $this->write($bible->getDescription(), 'white');
        $this->write("Wie soll die neue lauten? (stop zum abbrechen)");
        $in = trim(fgets($handle));
        if($in === 'stop') {
            return;
        }
        $bible->setDescription($in);
        $bible->addHistory("Neue Beschreibung: $in");
        $this->write("gemacht, Enter für weiter ^^");
        fgets($handle);
    }

    private function showMostCommonWords($handle, mixed $bible)
    {
        system('clear');
        $this->write("Die häufigsten Wörter in dieser Biebel sind:", '');
    }
}