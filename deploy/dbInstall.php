<?php
echo("Zoneminder DB Install script");

$mjdm_dir = '/var/www/html/';
include_once($mjdm_dir."config.php");
include_once($mjdm_dir."lib/loader.php");
include_once($mjdm_dir."load_settings.php");
include_once($mjdm_dir."modules/zoneminder/zoneminder.class.php");

$zm = new zoneminder();
$zm->dbInstall();