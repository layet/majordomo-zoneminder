<?php
include_once("config.php");
include_once("lib/loader.php");
include_once("load_settings.php");
include_once(DIR_MODULES . "zoneminder/zoneminder.class.php");

$monitor = htmlspecialchars($_GET["id"]);
$scale = htmlspecialchars($_GET["scale"]);

$zm = new zoneminder();
$zm->image($monitor, $scale);
?>