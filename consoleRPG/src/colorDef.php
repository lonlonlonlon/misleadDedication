<?php

const TERM_RESET = "\033[0m";

const TERM_BACK_WHITE_DARK="\033[47m";       # White
const TERM_BACK_YELLOW_DARK="\033[43m";      # Yellow
const TERM_BACK_GREEN_DARK="\033[42m";       # Green
const TERM_BACK_CYAN_DARK="\033[46m";        # Cyan
const TERM_BACK_BLUE_DARK="\033[44m";        # Blue
const TERM_BACK_PURPLE_DARK="\033[45m";      # Purple
const TERM_BACK_RED_DARK="\033[41m";         # Red
const TERM_BACK_BLACK_DARK="\033[40m";       # Black


const TERM_BACK_BLACK_LIGHT="\033[0;100m";   # Black
const TERM_BACK_RED_LIGHT="\033[0;101m";     # Red
const TERM_BACK_GREEN_LIGHT="\033[0;102m";   # Green
const TERM_BACK_YELLOW_LIGHT="\033[0;103m";  # Yellow
const TERM_BACK_BLUE_LIGHT="\033[0;104m";    # Blue
const TERM_BACK_PURPLE_LIGHT="\033[0;105m";  # Purple
const TERM_BACK_CYAN_LIGHT="\033[0;106m";    # Cyan
const TERM_BACK_WHITE_LIGHT="\033[0;107m";   # White

const TERM_FORE_BLACK="\e[0;30m";
const TERM_FORE_GRAY="\e[1;30m";
const TERM_FORE_RED="\e[0;31m";
const TERM_FORE_LIGHT_RED="\e[1;31m";
const TERM_FORE_GREEN="\e[0;32m";
const TERM_FORE_LIGHT_GREEN="\e[1;32m";
const TERM_FORE_BROWN="\e[0;33m";
const TERM_FORE_YELLOW="\e[1;33m";
const TERM_FORE_BLUE="\e[0;34m";
const TERM_FORE_LIGHT_BLUE="\e[1;34m";
const TERM_FORE_PURPLE="\e[0;35m";
const TERM_FORE_LIGHT_PURPLE="\e[1;35m";
const TERM_FORE_CYAN="\e[0;36m";
const TERM_FORE_LIGHT_CYAN="\e[1;36m";
const TERM_FORE_LIGHT_GRAY="\e[0;37m";
const TERM_FORE_WHITE="\e[1;37m";

const COLOR_DEFS = [
    "TERM_RESET" => TERM_RESET,
    "TERM_BACK_WHITE_DARK" => TERM_BACK_WHITE_DARK,
    "TERM_BACK_YELLOW_DARK" => TERM_BACK_YELLOW_DARK,
    "TERM_BACK_GREEN_DARK" => TERM_BACK_GREEN_DARK,
    "TERM_BACK_CYAN_DARK" => TERM_BACK_CYAN_DARK,
    "TERM_BACK_BLUE_DARK" => TERM_BACK_BLUE_DARK,
    "TERM_BACK_PURPLE_DARK" => TERM_BACK_PURPLE_DARK,
    "TERM_BACK_RED_DARK" => TERM_BACK_RED_DARK,
    "TERM_BACK_BLACK_DARK" => TERM_BACK_BLACK_DARK,
    "TERM_BACK_BLACK_LIGHT" => TERM_BACK_BLACK_LIGHT,
    "TERM_BACK_RED_LIGHT" => TERM_BACK_RED_LIGHT,
    "TERM_BACK_GREEN_LIGHT" => TERM_BACK_GREEN_LIGHT,
    "TERM_BACK_YELLOW_LIGHT" => TERM_BACK_YELLOW_LIGHT,
    "TERM_BACK_BLUE_LIGHT" => TERM_BACK_BLUE_LIGHT,
    "TERM_BACK_PURPLE_LIGHT" => TERM_BACK_PURPLE_LIGHT,
    "TERM_BACK_CYAN_LIGHT" => TERM_BACK_CYAN_LIGHT,
    "TERM_BACK_WHITE_LIGHT" => TERM_BACK_WHITE_LIGHT,
    "TERM_FORE_BLACK" => TERM_FORE_BLACK,
    "TERM_FORE_GRAY" => TERM_FORE_GRAY,
    "TERM_FORE_RED" => TERM_FORE_RED,
    "TERM_FORE_LIGHT_RED" => TERM_FORE_LIGHT_RED,
    "TERM_FORE_GREEN" => TERM_FORE_GREEN,
    "TERM_FORE_LIGHT_GREEN" => TERM_FORE_LIGHT_GREEN,
    "TERM_FORE_BROWN" => TERM_FORE_BROWN,
    "TERM_FORE_YELLOW" => TERM_FORE_YELLOW,
    "TERM_FORE_BLUE" => TERM_FORE_BLUE,
    "TERM_FORE_LIGHT_BLUE" => TERM_FORE_LIGHT_BLUE,
    "TERM_FORE_PURPLE" => TERM_FORE_PURPLE,
    "TERM_FORE_LIGHT_PURPLE" => TERM_FORE_LIGHT_PURPLE,
    "TERM_FORE_CYAN" => TERM_FORE_CYAN,
    "TERM_FORE_LIGHT_CYAN" => TERM_FORE_LIGHT_CYAN,
    "TERM_FORE_LIGHT_GRAY" => TERM_FORE_LIGHT_GRAY,
    "TERM_FORE_WHITE" => TERM_FORE_WHITE,
];

// 128 Cols 37 lines
function displayColorTest () {
    $lineBreak = false;
    foreach (get_defined_constants() as $key => $val) {
        if ($key === 'TERM_RESET') {
            continue;
        }
        if ($lineBreak) {
            $whitespace = PHP_EOL;
            $lineBreak = false;
        } else {
            $whitespace = "\t";
            $lineBreak = true;
        }
        if (str_starts_with($key, 'TERM_')) {
            echo($val . $key . TERM_RESET . $whitespace);
        }
    }
}