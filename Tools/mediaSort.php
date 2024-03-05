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
try {
    set_error_handler(function() {});

    mkdir("sorted_media");
    chdir("sorted_media");
    mkdir("video");
    mkdir("audio");
    mkdir("image");

    restore_error_handler();
} catch (Exception $exception) {
    echo("Unable to create directories: ".$exception->getMessage().PHP_EOL);
}

define("target_dir", getcwd());

$result = new stdClass();
$result->image = [];
$result->video = [];
$result->audio = [];

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
            $result->image[] = $path.'/'.$item;
            copy($path.'/'.$item, target_dir.'/image/'.$item);
        }
        if (in_array($fileExtension, videoFormats)) {
            $result->video[] = $path.'/'.$item;
            copy($path.'/'.$item, target_dir.'/video/'.$item);
        }
        if (in_array($fileExtension, audioFormats)) {
            $result->audio[] = $path.'/'.$item;
            copy($path.'/'.$item, target_dir.'/audio/'.$item);
        }
    }
}

recur(realpath($argv[1]), $result);

echo("Results:".PHP_EOL);
echo("video: ".count($result->video).PHP_EOL);
echo("audio: ".count($result->audio).PHP_EOL);
echo("image: ".count($result->image).PHP_EOL);
$date = (new DateTime('now'))->format('d-m-Y_H.i.s');
file_put_contents($date.'.stdClass', var_export($result, true));