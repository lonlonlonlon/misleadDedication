<?php
include_once 'src/Display.php';
include_once 'src/MainLoop.php';
include_once 'src/CliComponent.php';
$mainLoop = new MainLoop();
$mainLoop->start();