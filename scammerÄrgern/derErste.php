<?php
include_once "vendor/autoload.php";
include_once "Spam.php";

$spam = new Spam("https://eu.biziqo.click/sweeps20/", true);
$spam->spam();