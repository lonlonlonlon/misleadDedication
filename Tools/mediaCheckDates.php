<?php

const imageFormats=["3dm","3ds","max","avif","bmp","dds","gif","heif","jpg","jpeg","jxl","png","psd","xcf","tga","thm","tif","tiff","yuv","ai","eps","ps","svg","dwg","dxf","gpx","kml","kmz","webp"];
const videoFormats=["3g2","3gp","aaf","asf","avchd","avi","drc","flv","m2v","m4p","m4v","mkv","mng","mov","mp2","mp4","mpe","mpeg","mpg","mpv","mxf","nsv","ogv","ogm","ogx","qt","rm","rmvb","roq","srt","svi","vob","webm","wmv","yuv"];
const audioFormats=["aac","aiff","ape","au","flac","gsm","it","m3u","m4a","mid","mod","mp3","mpa","ogg","pls","ra","s3m","sid","wav","wma","xm"];

if (empty($argv[1])) {
    echo("No path provided.".PHP_EOL);
    exit();
}

try {
    chdir($argv[1]);
} catch (Exception $exception) {
    echo($argv[1]." is not a valid path: ".$exception->getMessage().PHP_EOL);
}

define("target_dir", getcwd());

$result = new stdClass();
$result->image = [];
$result->video = [];
$result->audio = [];

$GLOBALS['mediaNr'] = 0;

recur(realpath($argv[1]), $result);

$imagesWithoutDate = [];
foreach ($result->image as $file => $date) {
    if (empty($date)) {
        $imagesWithoutDate[] = $file;
    }
}

$videosWithoutDate = [];
foreach ($result->video as $file => $date) {
    if (empty($date)) {
        $videosWithoutDate[] = $file;
    }
}

echo("Results:".PHP_EOL);
echo("video: ".count($result->video).PHP_EOL);
echo("audio: ".count($result->audio).PHP_EOL);
echo("image: ".count($result->image).PHP_EOL);
echo("videos without date: ".count($videosWithoutDate).PHP_EOL);
echo("images without date: ".count($imagesWithoutDate).PHP_EOL);
echo("videos without date percent: ".(count($videosWithoutDate) / count($result->video)) * 100);
echo("videos without date percent: ".(count($imagesWithoutDate) / count($result->image)) * 100);
$date = (new DateTime('now'))->format('d-m-Y_H.i.s');
file_put_contents($date.'.stdClass', var_export($result, true));


function getVideoDate(string $video)
{
    $return = shell_exec('ffprobe -show_data -hide_banner '.$video.' 2>&1');
    $lines = explode("\n", $return);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) {
            continue;
        }
        if (str_contains($line, 'creation_time')) {
            $rawDate = trim(explode(' : ', $line)[1]);
            $date = new DateTime($rawDate);
            $dateStr = $date->format('d-m-Y___H-i-s');
            return $dateStr;
            break;
        }
    }
    return '';
}

function getImageDate(string $image) // pgn's und webp's werden nicht von exfi unterstützt, nochmal gucken
{
    $exfiData = exif_read_data($image, true, false);
    $date = DateTime::createFromFormat('U', $exfiData['FileDateTime']);
    if ($date === false) {
        return '';
    }
    return $date->format('d-m-Y___H-i-s');
}

function recur(string $path, object &$result) {
    foreach (scandir($path) as $item) {
        if ($item == '.' || $item == '..') {continue;}
        if (realpath($path.'/'.$item) == target_dir) {continue;}
        if (is_dir($path.'/'.$item)) {
            recur($path.'/'.$item, $result);
            continue;
        }
        $fileExtension = explode('.', $item);
        $fileExtension = $fileExtension[count($fileExtension)-1];
        if (in_array($fileExtension, imageFormats)) {
            try {
                $dateStr = getImageDate($path . '/' . $item);
                $result->image[$path . '/' . $item] = empty($dateStr)? $GLOBALS['mediaNr'] : $dateStr;
            } catch (Exception $exception) {
                $result->image[$path . '/' . $item] = $GLOBALS['mediaNr'];
            }
        }
        if (in_array($fileExtension, videoFormats)) {
            try {
                $dateStr = getVideoDate($path . '/' . $item);
                $result->video[$path . '/' . $item] = empty($dateStr)? $GLOBALS['mediaNr'] : $dateStr;
            } catch (Exception $exception) {
                $result->video[$path . '/' . $item] = $GLOBALS['mediaNr'];
            }
        }
//        if (in_array($fileExtension, audioFormats)) { // Audio will Knuff nicht
//            $result->audio[] = $path.'/'.$item;
//            copy($path.'/'.$item, target_dir.'/audio/'.$item);
//        }
        $GLOBALS['mediaNr'] += 1;
    }
}